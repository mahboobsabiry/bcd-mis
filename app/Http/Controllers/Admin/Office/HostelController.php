<?php

namespace App\Http\Controllers\Admin\Office;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHostelRequest;
use App\Models\Office\Hostel;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HostelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:office_hostel_view', ['only' => ['index', 'show']]);
        $this->middleware('permission:office_hostel_create', ['only' => ['create','store']]);
        $this->middleware('permission:office_hostel_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:office_hostel_delete', ['only' => ['destroy']]);
    }

    // Fetch All Data with optimized queries
    public function index()
    {
        // Use pagination for large datasets
        $hostels = Hostel::with([
            'place',
            'employees' => function($query) {
                $query->select('id', 'name', 'last_name', 'hostel_id');
            }
        ])
            ->withCount('employees')
            ->orderBy('place_id')
            ->orderBy('section')
            ->orderBy('number')
            ->paginate(25); // Paginate for better performance

        // Get places with hostel counts (optimized)
        $places = Place::select('id', 'name', 'code')
            ->withCount('hostels')
            ->whereHas('hostels')
            ->orderBy('name')
            ->get();

        // Statistics for dashboard
        $statistics = [
            'total_hostels' => Hostel::count(),
            'total_capacity' => Hostel::sum('capacity'),
            'total_occupied' => DB::table('employees')->whereNotNull('hostel_id')->count(),
            'available_capacity' => Hostel::sum('capacity') - DB::table('employees')->whereNotNull('hostel_id')->count(),
            'occupancy_rate' => Hostel::sum('capacity') > 0 ?
                round((DB::table('employees')->whereNotNull('hostel_id')->count() / Hostel::sum('capacity')) * 100, 1) : 0,
        ];

        return view('admin.office.hostel.index', compact('hostels', 'places', 'statistics'));
    }

    // Create
    public function create()
    {
        $places = Place::select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        // Generate suggested room number
        $suggestedNumber = Hostel::max('number') + 1;

        return view('admin.office.hostel.create', compact('places', 'suggestedNumber'));
    }

    // Store Data with transaction
    public function store(StoreHostelRequest $request)
    {
        try {
            DB::beginTransaction();

            $hostel = Hostel::create($request->validated());

            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($hostel)
                ->log('created hostel');

            DB::commit();

            return redirect()
                ->route('admin.office.hostel.index')
                ->with([
                    'message'   => 'اتاق خوابگاه موفقانه ثبت شد.',
                    'alertType' => 'success'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with([
                    'message'   => 'خطا در ثبت اتاق: ' . $e->getMessage(),
                    'alertType' => 'error'
                ]);
        }
    }

    // Show with optimized loading
    public function show(Hostel $hostel)
    {
        // Eager load all necessary relationships
        $hostel->load([
            'place',
            'employees.position',
            'employees.position_code',
            'employees.photo'
        ]);

        // Calculate statistics
        $occupancyPercentage = $hostel->capacity > 0 ?
            round(($hostel->employees->count() / $hostel->capacity) * 100, 1) : 0;

        // Get similar hostels for suggestions
        $similarHostels = Hostel::where('place_id', $hostel->place_id)
            ->where('id', '!=', $hostel->id)
            ->withCount('employees')
            ->orderBy('section')
            ->orderBy('number')
            ->limit(5)
            ->get();

        return view('admin.office.hostel.show', compact(
            'hostel',
            'occupancyPercentage',
            'similarHostels'
        ));
    }

    // Edit
    public function edit(Hostel $hostel)
    {
        $places = Place::select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        return view('admin.office.hostel.edit', compact('hostel', 'places'));
    }

    // Update Data with validation and transaction
    public function update(Request $request, Hostel $hostel)
    {
        $validated = $request->validate([
            'place_id'    => 'required|exists:places,id',
            'number'      => 'required|integer|min:1',
            'section'     => 'nullable|string|max:10',
            'capacity'    => 'required|integer|min:1|max:10',
            'status'      => 'nullable|boolean',
            'info'        => 'nullable|string|max:500'
        ]);

        // Check if room capacity is sufficient for current occupants
        if (isset($validated['capacity']) && $validated['capacity'] < $hostel->employees->count()) {
            return back()
                ->withInput()
                ->with([
                    'message'   => 'ظرفیت جدید کمتر از تعداد اعضای فعلی است.',
                    'alertType' => 'error'
                ]);
        }

        try {
            DB::beginTransaction();

            $oldData = $hostel->toArray();
            $hostel->update($validated);

            // Log changes
            activity()
                ->causedBy(auth()->user())
                ->performedOn($hostel)
                ->withProperties(['old' => $oldData, 'new' => $validated])
                ->log('updated hostel');

            DB::commit();

            return redirect()
                ->route('admin.office.hostel.index')
                ->with([
                    'message'   => 'اطلاعات اتاق موفقانه بروزرسانی شد.',
                    'alertType' => 'success'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with([
                    'message'   => 'خطا در بروزرسانی: ' . $e->getMessage(),
                    'alertType' => 'error'
                ]);
        }
    }

    // Delete Data with safety checks
    public function destroy(Hostel $hostel)
    {
        // Check if hostel has employees
        if ($hostel->employees()->count() > 0) {
            return back()
                ->with([
                    'message'   => 'این اتاق دارای کارمند می‌باشد. ابتدا کارمندان را انتقال دهید.',
                    'alertType' => 'error'
                ]);
        }

        try {
            DB::beginTransaction();

            $deletedData = $hostel->toArray();
            $hostel->delete();

            // Log deletion
            activity()
                ->causedBy(auth()->user())
                ->withProperties(['deleted_data' => $deletedData])
                ->log('deleted hostel');

            DB::commit();

            return redirect()
                ->route('admin.office.hostel.index')
                ->with([
                    'message'   => 'اتاق خوابگاه موفقانه حذف شد.',
                    'alertType' => 'success'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with([
                    'message'   => 'خطا در حذف اتاق: ' . $e->getMessage(),
                    'alertType' => 'error'
                ]);
        }
    }

    // Additional: Bulk assign employees
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id'
        ]);

        $hostel = Hostel::findOrFail($validated['hostel_id']);

        // Check capacity
        $currentOccupants = $hostel->employees()->count();
        $newOccupants = count($validated['employee_ids']);
        $availableCapacity = $hostel->capacity - $currentOccupants;

        if ($newOccupants > $availableCapacity) {
            return response()->json([
                'success' => false,
                'message' => 'ظرفیت اتاق کافی نیست. فقط ' . $availableCapacity . ' جای خالی موجود است.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update employees' hostel
            foreach ($validated['employee_ids'] as $employeeId) {
                DB::table('employees')
                    ->where('id', $employeeId)
                    ->update(['hostel_id' => $hostel->id]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $newOccupants . ' کارمند با موفقیت به اتاق ' . $hostel->number . ' منتقل شدند.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'خطا در انتقال: ' . $e->getMessage()
            ], 500);
        }
    }

    // Additional: Get available hostels by place
    public function getByPlace($placeId)
    {
        $hostels = Hostel::where('place_id', $placeId)
            ->withCount('employees')
            ->havingRaw('employees_count < capacity')
            ->orderBy('section')
            ->orderBy('number')
            ->get(['id', 'number', 'section', 'capacity', DB::raw('capacity - (SELECT COUNT(*) FROM employees WHERE hostel_id = hostels.id) as available')]);

        return response()->json($hostels);
    }
}
