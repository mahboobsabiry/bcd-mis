<?php

namespace App\Http\Controllers\Admin\Office;

use App\Http\Controllers\Controller;
use App\Models\Office\Position;
use App\Models\Office\PositionCode;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:office_position_view', ['only' => ['index', 'show', 'appointment', 'empty', 'inactive']]);
        $this->middleware('permission:office_position_create', ['only' => ['create','store']]);
        $this->middleware('permission:office_position_edit', ['only' => ['edit','update', 'updatePositionStatus']]);
        $this->middleware('permission:office_position_delete', ['only' => ['destroy']]);
        $this->middleware(function ($request, $next) {
            if ($request->ajax()) {
                // Disable debugbar for AJAX requests
                if (class_exists('Barryvdh\Debugbar\LaravelDebugbar')) {
                    \Debugbar::disable();
                }
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Use pagination for initial load
        $perPage = $request->get('per_page', 25);

        // Base query with SELECT only needed columns and optimized eager loading
        $query = Position::select([
            'id',
            'title',
            'desc',
            'position_number',
            'num_of_pos',
            'parent_id',
            'place_id',
            'status',
            'created_at'
        ])->with([
            'parent:id,title', // Only need title from parent
            'place:id,name,custom_code',
            // Optimize codes relationship - load only what's needed
            'codes' => function ($q) {
                $q->select(['id', 'position_id', 'code'])
                    ->with(['employee:id,ps_code_id,name,last_name']);
            }
        ]);

        // Add counts as separate queries to avoid N+1 but more efficiently
        $query->withCount([
            'codes',
            'codes as filled_codes_count' => function($query) {
                $query->whereHas('employee');
            }
        ]);

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('desc', 'like', "%{$search}%")
                    ->orWhere('position_number', 'like', "%{$search}%");
            });
        }

        // Apply level filter first (it's cheaper than status filter)
        if ($request->has('level')) {
            $level = $request->level;
            if ($level == '7') {
                $query->where('position_number', '>=', 7);
            } else {
                $query->where('position_number', $level);
            }
        }

        // Order by position_number to see hierarchy
        $positions = $query->orderBy('position_number', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);

        // Get basic statistics without loading all data
        $stats = $this->getPositionsStats();

        // For organization tree - get pre-calculated tree data
        $organizationData = $this->getOrganizationTreeData();

        return view('admin.office.positions.index', compact('positions', 'organizationData', 'stats'));
    }

    /**
     * Get positions statistics without loading all records
     */
    private function getPositionsStats()
    {
        return Cache::remember('positions_stats', 300, function () { // Cache for 5 minutes
            return [
                'total_positions' => PositionCode::count(),
                'filled_positions' => PositionCode::whereHas('employee')->count(),
                'empty_positions' => PositionCode::whereDoesntHave('employee')->count(),
                'uncoded_positions' => Position::doesntHave('codes')->count(),
            ];
        });
    }

    /**
     * Get organization tree data efficiently with tree structure already built
     */
    private function getOrganizationTreeData()
    {
        return Cache::remember('organization_tree_data', 300, function () {
            $items = Position::select(['id', 'title', 'position_number', 'parent_id', 'num_of_pos'])
                ->withCount(['codes as filled_codes_count' => function($query) {
                    $query->whereHas('employee');
                }])
                ->get()
                ->map(function ($position) {
                    return [
                        'id' => $position->id,
                        'title' => $position->title,
                        'position_number' => $position->position_number,
                        'parent_id' => $position->parent_id,
                        'num_of_pos' => $position->num_of_pos,
                        'filled_codes_count' => $position->filled_codes_count,
                        'percentage' => $position->num_of_pos > 0
                            ? round(($position->filled_codes_count / $position->num_of_pos) * 100)
                            : 0,
                        'status' => $position->filled_codes_count >= $position->num_of_pos ? 'filled' : 'vacant'
                    ];
                })
                ->toArray();

            // Build tree structure in PHP (this is more efficient than doing it in view)
            $tree = [];
            $itemsByParent = [];

            // Group items by parent_id
            foreach ($items as $item) {
                $parentId = $item['parent_id'] ?? 0;
                if (!isset($itemsByParent[$parentId])) {
                    $itemsByParent[$parentId] = [];
                }
                $itemsByParent[$parentId][] = $item;
            }

            // Build tree recursively
            $buildTree = function($parentId = null) use (&$buildTree, $itemsByParent) {
                $tree = [];
                if (isset($itemsByParent[$parentId])) {
                    foreach ($itemsByParent[$parentId] as $item) {
                        $children = $buildTree($item['id']);
                        if ($children) {
                            $item['children'] = $children;
                        }
                        $tree[] = $item;
                    }
                }
                return $tree;
            };

            $tree = $buildTree(null);

            // Find root position
            $rootPosition = null;
            if (count($tree) > 0) {
                $rootPosition = $tree[0]; // First item in root level
            } elseif (count($items) > 0) {
                $rootPosition = $items[0]; // Fallback to first item
            }

            // Calculate statistics
            $positionStats = [];
            $totalPositions = 0;
            $totalFilled = 0;
            $totalVacant = 0;

            foreach($items as $position) {
                $grade = $position['position_number'];
                if(!isset($positionStats[$grade])) {
                    $positionStats[$grade] = [
                        'count' => 0,
                        'filled' => 0,
                        'vacant' => 0,
                        'title' => 'بست ' . $grade
                    ];
                }
                $positionStats[$grade]['count']++;
                $totalPositions += $position['num_of_pos'];

                $filled = $position['filled_codes_count'];
                $positionStats[$grade]['filled'] += $filled;
                $positionStats[$grade]['vacant'] += ($position['num_of_pos'] - $filled);

                $totalFilled += $filled;
                $totalVacant += ($position['num_of_pos'] - $filled);
            }

            ksort($positionStats);
            $fillRate = ($totalFilled + $totalVacant) > 0 ? round(($totalFilled / ($totalFilled + $totalVacant)) * 100) : 0;

            return [
                'tree' => $tree,
                'rootPosition' => $rootPosition,
                'positionStats' => $positionStats,
                'totalPositions' => $totalPositions,
                'totalFilled' => $totalFilled,
                'totalVacant' => $totalVacant,
                'fillRate' => $fillRate,
                'items' => $items // Keep flat array for easy access
            ];
        });
    }

    public function getOrgChart()
    {
        $organizationData = $this->getOrganizationTreeData();

        // Pass items as flat array for the tree rendering
        return view('admin.office.positions.inc.org_tab', [
            'organizationData' => $organizationData
        ]);
    }

    // Create
    public function create()
    {
        $positions = Position::all();
        $places = Place::all();
        Cache::forget('positions_stats');
        Cache::forget('organization_tree');
        return view('admin.office.positions.create', compact('positions', 'places'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:positions,id',
            'place_id' => 'required|exists:places,id',
            'title' => 'required|string|max:255',
            'position_number' => 'required|integer|min:1',
            'num_of_pos' => 'required|integer|min:1',
            'desc' => 'nullable|string',
            'status' => 'nullable|boolean',
            'codes' => 'nullable|array',
            'codes.*' => 'string|max:50',
        ]);

        try {
            DB::beginTransaction();

            // Create the position
            $position = Position::create([
                'parent_id' => $validated['parent_id'] ?? null,
                'place_id' => $validated['place_id'],
                'title' => $validated['title'],
                'position_number' => $validated['position_number'],
                'num_of_pos' => $validated['num_of_pos'],
                'desc' => $validated['desc'] ?? null,
                'status' => $validated['status'] ?? true,
            ]);

            // Create position codes if provided
            if (!empty($validated['codes'])) {
                foreach ($validated['codes'] as $codeValue) {
                    $position->codes()->create([
                        'code' => trim($codeValue),
                        'status' => 1,
                        'info' => "Created with position {$position->title}"
                    ]);
                }
            }

            // Log activity
            activity('created')
                ->causedBy(Auth::user())
                ->performedOn($position)
                ->withProperties([
                    'codes_created' => count($validated['codes'] ?? []),
                    'parent_id' => $position->parent_id
                ])
                ->log("بست {$position->title} ایجاد شد");

            DB::commit();

            return redirect()->route('admin.office.positions.index')
                ->with([
                    'message' => 'بست با موفقیت ایجاد شد.',
                    'alertType' => 'success'
                ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Position creation failed: ' . $e->getMessage(), [
                'request' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with([
                    'message' => 'خطا در ایجاد بست. لطفاً دوباره تلاش کنید.',
                    'alertType' => 'error'
                ]);
        }
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
        Cache::forget('positions_stats');
        Cache::forget('organization_tree');
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
            $usersToNotify = User::whereHas('roles', function($query) {
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
            Cache::forget('positions_stats');
            Cache::forget('organization_tree');
            return back()->with([
                'message'   => 'بست با موفقیت حذف شد.',
                'alertType' => 'success'
            ]);

        } catch (\Exception $e) {
            \Log::error('Position deletion error: ' . $e->getMessage());
            Cache::forget('positions_stats');
            Cache::forget('organization_tree');
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

    // PositionController.php - Updated appointed() method
    public function appointed()
    {
        // Eager load relationships for better performance
        $codes = PositionCode::with(['employee', 'position.parent'])
            ->whereHas('employee')
            ->where('status', 1) // Only active position codes
            ->orderBy('code')
            ->get();

        return view('admin.office.positions.appointed', compact('codes'));
    }

    // PositionController.php - Updated empty() method
    public function empty()
    {
        // Get empty position codes with active status
        $codes = PositionCode::with(['position.parent', 'position.place'])
            ->whereDoesntHave('employee')
            ->where('status', 1) // Only active empty positions
            ->orderBy('code')
            ->get();

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
