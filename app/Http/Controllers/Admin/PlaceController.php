<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:place_view', ['only' => ['index', 'show']]);
        $this->middleware('permission:place_create', ['only' => ['create','store']]);
        $this->middleware('permission:place_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:place_delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        // Load places with counts using proper relationship definitions
        $places = Place::withCount([
            'positions',
            'positions as positions_codes_count' => function ($query) {
                $query->select(\DB::raw('SUM(
                (SELECT COUNT(*) FROM position_codes
                 WHERE position_codes.position_id = positions.id)
            )'));
            }
        ])->orderBy('created_at', 'desc')->get();

        if ($places->count() < 1) {
            $code = "P01";
        } else {
            $latestId = Place::max('id') + 1;
            $code = "P" . str_pad($latestId, 2, '0', STR_PAD_LEFT);
        }

        return view('admin.places.index', compact('places', 'code'));
    }

    // Store
    public function store(Request $request)
    {
        // Enhanced Validation
        $validated = $request->validate([
            'name'          => 'required|string|max:255|unique:places,name',
            'code'          => 'nullable|string|max:20|unique:places,code',
            'custom_code'   => 'nullable|string|max:50|unique:places,custom_code',
            'info'          => 'nullable|string',
            'status'        => 'nullable|boolean'
        ]);

        // Generate code if not provided
        if (empty($validated['code'])) {
            $lastPlace = Place::latest()->first();
            $nextId = $lastPlace ? $lastPlace->id + 1 : 1;
            $validated['code'] = 'P' . str_pad($nextId, 2, '0', STR_PAD_LEFT);
        }

        // Ensure status is set (default: active)
        $validated['status'] = $validated['status'] ?? true;

        try {
            DB::beginTransaction();

            $place = Place::create($validated);

            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($place)
                ->log('created');

            DB::commit();

            return redirect()
                ->route('admin.places.index')
                ->with([
                    'message'   => 'موقعیت جدید موفقانه ایجاد گردید.',
                    'alertType' => 'success',
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with([
                    'message'   => 'خطا در ایجاد موقعیت: ' . $e->getMessage(),
                    'alertType' => 'error',
                ]);
        }
    }

    // Show method with enhanced data loading
    public function show(Place $place)
    {
        // Load all necessary relationships with counts
        $place->load([
            'positions.codes.employee',
            'positions.parent',
            'hostels',
            'users' => function($query) {
                $query->with('employee');
            },
            'asycuda_users'
        ]);

        // Calculate statistics
        $totalCodes = 0;
        $occupiedCodes = 0;
        $vacantCodes = 0;

        foreach ($place->positions as $position) {
            $totalCodes += $position->codes->count();
            $occupiedCodes += $position->codes->where('employee')->count();
            $vacantCodes += $position->codes->whereDoesntHave('employee')->count();
        }

        $statistics = [
            'total_positions' => $place->positions->count(),
            'total_codes' => $totalCodes,
            'occupied_codes' => $occupiedCodes,
            'vacant_codes' => $vacantCodes,
            'total_hostels' => $place->hostels->count(),
            'total_users' => $place->users->count(),
            'total_asycuda_users' => $place->asycuda_users->count(),
        ];

        // Get recent activities (you might want to implement this)
        $recentActivities = []; // Placeholder for activity logs

        return view('admin.places.show', compact('place', 'statistics', 'recentActivities'));
    }

    // Update
    public function update(Request $request, Place $place)
    {
        // Enhanced Validation
        $validated = $request->validate([
            'name'          => 'required|string|max:255|unique:places,name,' . $place->id,
            'code'          => 'nullable|string|max:20|unique:places,code,' . $place->id,
            'custom_code'   => 'nullable|string|max:50|unique:places,custom_code,' . $place->id,
            'info'          => 'nullable|string',
            'status'        => 'nullable|boolean'
        ]);

        try {
            DB::beginTransaction();

            // Store old data for logging
            $oldData = $place->toArray();

            // Update place
            $place->update($validated);

            // Log changes
            activity()
                ->causedBy(auth()->user())
                ->performedOn($place)
                ->withProperties([
                    'old' => $oldData,
                    'new' => $validated
                ])
                ->log('updated');

            DB::commit();

            return redirect()
                ->route('admin.places.index')
                ->with([
                    'message'   => 'موقعیت موفقانه ویرایش گردید.',
                    'alertType' => 'success',
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with([
                    'message'   => 'خطا در ویرایش موقعیت: ' . $e->getMessage(),
                    'alertType' => 'error',
                ]);
        }
    }

    // Delete with safety checks
    public function destroy(Request $request, Place $place)
    {
        // Check if place has positions
        if ($place->positions()->count() > 0) {
            return back()
                ->with([
                    'message'   => 'این موقعیت دارای منصب می‌باشد. ابتدا مناصب آن را حذف یا انتقال دهید.',
                    'alertType' => 'error',
                ]);
        }

        // Check if place has users
        if ($place->users()->count() > 0) {
            return back()
                ->with([
                    'message'   => 'این موقعیت دارای کاربر می‌باشد. ابتدا کاربران آن را انتقال دهید.',
                    'alertType' => 'error',
                ]);
        }

        // Check if place has hostels
        if ($place->hostels()->count() > 0) {
            return back()
                ->with([
                    'message'   => 'این موقعیت دارای خوابگاه می‌باشد. ابتدا خوابگاه‌های آن را حذف کنید.',
                    'alertType' => 'error',
                ]);
        }

        try {
            DB::beginTransaction();

            // Store data for logging
            $deletedData = $place->toArray();

            // Delete place
            $place->delete();

            // Log deletion
            activity()
                ->causedBy(auth()->user())
                ->withProperties(['deleted_data' => $deletedData])
                ->log('deleted place');

            DB::commit();

            return redirect()
                ->route('admin.places.index')
                ->with([
                    'message'   => 'موقعیت موفقانه حذف شد.',
                    'alertType' => 'success',
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with([
                    'message'   => 'خطا در حذف موقعیت: ' . $e->getMessage(),
                    'alertType' => 'error',
                ]);
        }
    }

    // Additional: Deactivate instead of delete
    public function deactivate(Request $request, Place $place)
    {
        try {
            $place->update(['status' => 0]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($place)
                ->log('deactivated');

            return back()
                ->with([
                    'message'   => 'موقعیت با موفقیت غیرفعال شد.',
                    'alertType' => 'success',
                ]);

        } catch (\Exception $e) {
            return back()
                ->with([
                    'message'   => 'خطا در غیرفعال سازی: ' . $e->getMessage(),
                    'alertType' => 'error',
                ]);
        }
    }

    // Additional: Activate
    public function activate(Request $request, Place $place)
    {
        try {
            $place->update(['status' => 1]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($place)
                ->log('activated');

            return back()
                ->with([
                    'message'   => 'موقعیت با موفقیت فعال شد.',
                    'alertType' => 'success',
                ]);

        } catch (\Exception $e) {
            return back()
                ->with([
                    'message'   => 'خطا در فعال سازی: ' . $e->getMessage(),
                    'alertType' => 'error',
                ]);
        }
    }

    // Additional: Transfer positions to another place
    public function transferPositions(Request $request, Place $place)
    {
        $request->validate([
            'target_place_id' => 'required|exists:places,id',
            'confirm' => 'required|accepted'
        ]);

        try {
            DB::beginTransaction();

            $targetPlace = Place::findOrFail($request->target_place_id);
            $positionsCount = $place->positions()->count();

            // Transfer positions
            $place->positions()->update(['place_id' => $targetPlace->id]);

            // Log the transfer
            activity()
                ->causedBy(auth()->user())
                ->performedOn($place)
                ->withProperties([
                    'target_place_id' => $targetPlace->id,
                    'transferred_positions' => $positionsCount
                ])
                ->log('transferred positions');

            DB::commit();

            return redirect()
                ->route('admin.places.index')
                ->with([
                    'message'   => $positionsCount . ' منصب با موفقیت به ' . $targetPlace->name . ' انتقال یافت.',
                    'alertType' => 'success',
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with([
                    'message'   => 'خطا در انتقال مناصب: ' . $e->getMessage(),
                    'alertType' => 'error',
                ]);
        }
    }
}
