<?php

namespace App\Http\Controllers\Admin\Office;

use App\Exports\PositionsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePositionRequest;
use App\Models\Office\Position;
use App\Models\Office\PositionCode;
use App\Models\Place;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:office_position_view', ['only' => ['index', 'show', 'appointment', 'empty', 'inactive']]);
        $this->middleware('permission:office_position_create', ['only' => ['create','store']]);
        $this->middleware('permission:office_position_edit', ['only' => ['edit','update', 'updatePositionStatus']]);
        $this->middleware('permission:office_position_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Use pagination for initial load
        $perPage = $request->get('per_page', 25);

        // Base query with eager loading
        $query = Position::with([
            'parent:id,title',
            'place:id,name,custom_code',
            'codes:id,position_id,code',
            'codes.employee:id,ps_code_id,name,last_name'
        ])
            ->withCount(['codes', 'employees'])
            ->withCount(['codes as filled_codes_count' => function($query) {
                $query->whereHas('employee');
            }]);

        // Apply filters if any
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('desc', 'like', "%{$search}%")
                    ->orWhere('position_number', 'like', "%{$search}%");
            });
        }

        // In PositionController index method, replace the status filter section:

        if ($request->has('status')) {
            $status = $request->status;

            switch ($status) {
                case 'full':
                    // Positions where all codes are filled
                    $query->whereHas('codes', function($q) {
                        $q->whereHas('employee');
                    });
                    break;

                case 'vacant':
                    // Positions with less codes than num_of_pos
                    $query->whereRaw('num_of_pos > (SELECT COUNT(*) FROM position_codes WHERE position_codes.position_id = positions.id)');
                    break;

                case 'uncoded':
                    // Positions with no codes
                    $query->whereDoesntHave('codes');
                    break;

                case 'empty':
                    // Positions with codes but no employees
                    $query->whereHas('codes', function($q) {
                        $q->whereDoesntHave('employee');
                    });
                    break;
            }
        }

        // Side
        if ($request->has('level')) {
            $level = $request->level;
            if ($level == '4') {
                $query->where('position_number', '>=', 4);
            } else {
                $query->where('position_number', $level);
            }
        }

        $positions = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Get all positions for organization tree (no filters)
        $organization = Position::tree();

        // Get all positions for DataTables (without pagination)
        $allPositionsForTable = Position::select(['id', 'title', 'position_number', 'num_of_pos', 'created_at'])
            ->withCount('codes')
            ->withCount(['codes as filled_codes_count' => function($query) {
                $query->whereHas('employee');
            }])
            ->get();

        return view('admin.office.positions.index', compact('positions', 'organization', 'allPositionsForTable'));
    }

    public function exportPDF(Request $request)
    {
        $positions = $this->getFilteredPositions($request);
        $lang = $request->get('lang', 'fa');

        $pdf = Pdf::loadView('admin.office.positions.export.pdf', [
            'positions' => $positions,
            'lang' => $lang,
            'date' => now()->format('Y/m/d H:i')
        ]);

        $pdf->setPaper('a4', 'portrait');
        return $pdf->download("positions_report_{$lang}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $lang = $request->get('lang', 'fa');
        return Excel::download(new PositionsExport($request), "positions_report_{$lang}.xlsx");
    }

    public function exportCSV(Request $request)
    {
        $lang = $request->get('lang', 'fa');
        return Excel::download(new PositionsExport($request), "positions_report_{$lang}.csv", \Maatwebsite\Excel\Excel::CSV);
    }

    private function getFilteredPositions($request = null)
    {
        $query = Position::with(['codes.employee', 'parent', 'place']);

        // If a request is provided (for exports), apply filters
        if ($request && $request instanceof \Illuminate\Http\Request) {
            // Apply status filter if provided
            if ($request->filled('status')) {
                $status = $request->get('status');
                if ($status === 'filled') {
                    $query->whereHas('codes.employee');
                } elseif ($status === 'empty') {
                    $query->whereDoesntHave('codes.employee');
                }
            }

            // Apply level filter if provided
            if ($request->filled('level')) {
                $query->where('position_number', $request->get('level'));
            }

            // Apply current DataTables filters if requested
            if ($request->filled('apply_filters') && $request->get('apply_filters')) {
                // Add any existing filter logic from your index method here
                // For example, search term filtering
                if ($request->filled('search')) {
                    $search = $request->get('search');
                    $query->where('title', 'like', "%{$search}%");
                }
            }
        }

        return $query->get();
    }

    // Create
    public function create()
    {
        $positions = Position::all();
        $places = Place::all();
        return view('admin.office.positions.create', compact('positions', 'places'));
    }

    // Store
    public function store(StorePositionRequest $request)
    {
        $position           = new Position();
        $position->parent_id    = $request->parent_id;
        $position->place_id     = $request->place_id;
        $position->title        = $request->title;
        $position->position_number = $request->position_number;
        $position->num_of_pos   = $request->num_of_pos;
        $position->desc         = $request->desc;
        $position->status       = 1;
        $position->save();

        // Store Code
        foreach ($request->codes as $value) {
            $position->codes()->create($value);
        }

        activity('added')
            ->causedBy(Auth::user())
            ->performedOn($position)
            ->log(trans('messages.positions.addedPositionMsg'));

        $message = trans('messages.positions.addedPositionMsg');
        return redirect()->route('admin.office.positions.show', $position->id)->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Show
    public function show(Position $position)
    {
        $position->load('employees');
        return view('admin.office.positions.show', compact('position'));
    }

    /**
     * Edit Position
     */
    public function edit(Position $position)
    {
        // Get all positions except the current one to prevent circular reference
        $positions = Position::where('id', '!=', $position->id)
            ->with('place')
            ->orderBy('title')
            ->get();

        $places = Place::orderBy('name')->get();

        return view('admin.office.positions.edit', compact('position', 'positions', 'places'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        // Enhanced validation rules
        $request->validate([
            'title'           => 'required|min:3|max:255',
            'position_number' => 'required|integer|min:1|max:10',
            'place_id'        => 'required|exists:places,id',
            'parent_id'       => 'nullable|exists:positions,id',
            'num_of_pos'      => 'required|integer|min:' . $position->codes()->count() . '|max:50',
            'desc'            => 'nullable|string|max:1000'
        ], [
            'title.required'           => 'عنوان بست الزامی است',
            'title.min'                => 'عنوان بست باید حداقل ۳ کاراکتر باشد',
            'position_number.required' => 'درجه بست الزامی است',
            'position_number.min'      => 'درجه بست باید حداقل ۱ باشد',
            'position_number.max'      => 'درجه بست نمی‌تواند بیشتر از ۱۰ باشد',
            'place_id.required'        => 'انتخاب موقعیت الزامی است',
            'num_of_pos.min'           => 'تعداد بست‌ها نمی‌تواند کمتر از تعداد کدهای تعریف شده باشد',
            'num_of_pos.max'           => 'تعداد بست‌ها نمی‌تواند بیشتر از ۵۰ باشد'
        ]);

        // Prevent circular reference (position can't be parent of itself)
        if ($request->parent_id == $position->id) {
            return back()->withInput()->withErrors([
                'parent_id' => 'بست نمی‌تواند مافوق خودش باشد'
            ]);
        }

        // Check for circular reference in hierarchy (position can't be ancestor of itself)
        if ($request->parent_id) {
            $parent = Position::find($request->parent_id);
            if ($this->isCircularReference($position, $parent)) {
                return back()->withInput()->withErrors([
                    'parent_id' => 'این انتخاب باعث ایجاد رابطه چرخشی در ساختار سازمانی می‌شود'
                ]);
            }
        }

        // Check if number of positions is being reduced below filled codes
        $filledCodesCount = $position->codes()->whereHas('employee')->count();
        if ($request->num_of_pos < $position->num_of_pos) {
            // If reducing positions, check if we have enough capacity
            if ($filledCodesCount > $request->num_of_pos) {
                return back()->withInput()->withErrors([
                    'num_of_pos' => 'تعداد بست‌ها نمی‌تواند کمتر از تعداد کدهای پر شده باشد'
                ]);
            }
        }

        // Store old values for activity log
        $oldValues = $position->getAttributes();

        try {
            // Update position
            $position->place_id        = $request->place_id;
            $position->parent_id       = $request->parent_id;
            $position->title           = $request->title;
            $position->position_number = $request->position_number;
            $position->num_of_pos      = $request->num_of_pos;
            $position->desc            = $request->desc;
            $position->save();

            // Log activity with detailed changes
            $changes = [];
            foreach ($oldValues as $key => $oldValue) {
                if ($position->$key != $oldValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $position->$key
                    ];
                }
            }

            activity('updated')
                ->causedBy(Auth::user())
                ->performedOn($position)
                ->withProperties([
                    'changes' => $changes,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ])
                ->log(trans('messages.positions.updatedPositionMsg'));

            // Send notification if important fields changed
            $this->sendUpdateNotifications($position, $oldValues);

            $message = trans('messages.positions.updatedPositionMsg');

            return redirect()->route('admin.office.positions.show', $position->id)
                ->with([
                    'message'   => $message,
                    'alertType' => 'success',
                    'changes'   => $changes // Optional: can be used to show what changed
                ]);

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Position update failed: ' . $e->getMessage(), [
                'position_id' => $position->id,
                'user_id' => Auth::id(),
                'request' => $request->except('_token', '_method')
            ]);

            return back()->withInput()->withErrors([
                'error' => 'خطا در به‌روزرسانی بست. لطفاً دوباره تلاش کنید.'
            ]);
        }
    }

    /**
     * Check for circular reference in position hierarchy
     */
    private function isCircularReference(Position $position, Position $parent): bool
    {
        $current = $parent;

        // Traverse up the hierarchy to check if we encounter the original position
        while ($current->parent_id) {
            if ($current->parent_id == $position->id) {
                return true; // Circular reference found
            }
            $current = Position::find($current->parent_id);
            if (!$current) break;
        }

        return false;
    }

    /**
     * Send notifications when important fields are changed
     */
    private function sendUpdateNotifications(Position $position, array $oldValues)
    {
        $importantFields = ['title', 'parent_id', 'position_number', 'num_of_pos'];
        $changedFields = [];

        foreach ($importantFields as $field) {
            if (isset($oldValues[$field]) && $position->$field != $oldValues[$field]) {
                $changedFields[] = $field;
            }
        }

        if (!empty($changedFields)) {
            // Notify relevant users (HR, managers, etc.)
            $usersToNotify = \App\Models\User::whereHas('roles', function($query) {
                $query->whereIn('name', ['hr_manager', 'office_manager']);
            })->get();

            foreach ($usersToNotify as $user) {
                // You can implement your notification system here
                // For example: $user->notify(new PositionUpdated($position, $changedFields));
            }

            // If position number changed, notify employees in this position
            if (in_array('position_number', $changedFields)) {
                $employees = $position->employees;
                foreach ($employees as $employee) {
                    if ($employee->user) {
                        // Notify employee about grade change
                        // $employee->user->notify(new PositionGradeChanged($position, $oldValues['position_number']));
                    }
                }
            }
        }
    }

    /**
     * Additional method for handling position code management
     */
    public function manageCodes(Position $position)
    {
        // This method would handle the position codes management
        // Called from the "Manage Codes" button in the edit page

        $codes = $position->codes()->with('employee')->orderBy('code')->get();

        return view('admin.office.positions.manage-codes', compact('position', 'codes'));
    }

    /**
     * Generate suggested codes for a position
     */
    public function generateCodes(Position $position)
    {
        // Generate suggested codes based on position title and place
        $title = $position->title;
        $place = $position->place;

        $placeCode = $place ? substr($place->name, 0, 2) : 'PO';
        $titleCode = substr($title, 0, 2);

        $existingCodes = $position->codes->pluck('code')->toArray();
        $suggestions = [];

        // Generate unique suggestions
        for ($i = 1; $i <= 10; $i++) {
            $code = strtoupper($placeCode . $titleCode . str_pad($i, 3, '0', STR_PAD_LEFT));
            if (!in_array($code, $existingCodes)) {
                $suggestions[] = $code;
            }
            if (count($suggestions) >= 5) break;
        }

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
            'position_id' => $position->id
        ]);
    }

    /**
     * Validate position before update (AJAX validation)
     */
    public function validatePosition(Request $request, Position $position = null)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255|unique:positions,title' . ($position ? ',' . $position->id : ''),
            'position_number' => 'required|integer|min:1|max:10',
            'parent_id' => 'nullable|exists:positions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        try {
            // Check if position has active employees
            if ($position->employees()->where('status', 'active')->exists()) {
                return back()->with([
                    'message'   => 'نمی‌توان بستی را که دارای کارمند فعال است حذف کرد.',
                    'alertType' => 'warning'
                ]);
            }

            // Detach any remaining employees
            $position->employees()->update(['position_id' => null]);

            // Delete child positions if they have no employees
            $position->children->each(function ($child) {
                if (!$child->employees()->exists()) {
                    $child->delete();
                }
            });

            // Log the deletion
            activity()
                ->causedBy(auth()->user())
                ->performedOn($position)
                ->log("بست {$position->title} حذف شد");

            // Delete the position
            $position->delete();

            return back()->with([
                'message'   => 'بست با موفقیت حذف شد.',
                'alertType' => 'success'
            ]);

        } catch (\Exception $e) {
            \Log::error('Position deletion error: ' . $e->getMessage());

            return back()->with([
                'message'   => 'خطا در حذف بست. لطفاً دوباره تلاش کنید.',
                'alertType' => 'danger'
            ]);
        }
    }

    // Update Status
    public function updatePositionStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Position::where('id', $data['position_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'position_id' => $data['position_id']]);
        }
    }

    // Appointment Positions
    public function appointment()
    {
        // Send appointment and empty positions count to dashboard
        // Sum number of positions
        // $sum_appointment = Position::all()->sum('num_of_pos');
        // Count all employees
        // $employees_count = Employee::all()->count();
        // Count all empty positions
        // $empty_positions = $sum_appointment - $employees_count;
        // Count all appointment positions
        // $appointment_positions = $sum_appointment - $empty_positions;
        $codes = PositionCode::whereHas('employee')->get();
        return view('admin.office.positions.appointment', compact('codes'));
    }

    // Empty Positions
    public function empty()
    {
        // Send appointment and empty positions count to dashboard
        // Sum number of positions
        // $sum_appointment = Position::all()->sum('num_of_pos');
        // Count all employees
        // $employees_count = Employee::all()->count();
        // Count all empty positions
        // $empty_positions = $sum_appointment - $employees_count;
        // $positions = Position::with('employees')->orderBy('created_at', 'desc')->get();
        $codes = PositionCode::whereDoesntHave('employee')->get();
        return view('admin.office.positions.empty', compact('codes'));
    }

    // Fetch All Data
    public function inactive()
    {
        $positions = Position::with('employees')->where('status', 0)->orderBy('created_at', 'desc')->get();
        return view('admin.office.positions.inactive', compact('positions'));
    }

    // Add Code
    public function add_code(Request $request, $id)
    {
        // Position
        $position = Position::find($id);

        // Return back if number of position is equal or greater than number of codes.
//        if ($position->num_of_pos >= $position->codes->count()) {
//            return back()->with([
//                'message'   => 'بست هذا گنجایش کد جدید را ندارد، لطفا تعداد بست را ابتدا تغییر بدهید.',
//                'alertType' => 'secondary'
//            ]);
//        }

        // Validate
        $request->validate([
            'code'  => 'required|numeric|max:999|unique:position_codes,code'
        ]);

        // Store
        $code = new PositionCode();
        $code->position_id  = $position->id;
        $code->code         = $request->code;
        $code->info         = $request->info;
        $code->save();

        return redirect()->back()->with([
            'message'   => 'کد بست موفقانه ذخیره گردید.',
            'alertType' => 'success'
        ]);
    }

    // Edit Code
    public function edit_code(Request $request, $id)
    {
        // Position Code
        $code = PositionCode::find($id);

        // Validate
        $request->validate([
            'code'  => 'required|numeric|max:999|unique:position_codes,code,'.$code->id,
        ]);

        // Edit
        $code->code         = $request->code;
        $code->info         = $request->info;
        $code->save();

        return redirect()->back()->with([
            'message'   => 'کد بست موفقانه ویرایش گردید.',
            'alertType' => 'success'
        ]);
    }
}
