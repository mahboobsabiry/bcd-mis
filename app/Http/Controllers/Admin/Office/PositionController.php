<?php

namespace App\Http\Controllers\Admin\Office;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePositionRequest;
use App\Models\Office\Employee;
use App\Models\Office\Position;
use App\Models\Office\PositionCode;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:office_position_view', ['only' => ['index', 'show', 'appointment', 'empty', 'inactive']]);
        $this->middleware('permission:office_position_create', ['only' => ['create','store']]);
        $this->middleware('permission:office_position_edit', ['only' => ['edit','update', 'updatePositionStatus']]);
        $this->middleware('permission:office_position_delete', ['only' => ['destroy']]);
    }

    // Fetch All Data
    public function index()
    {
        $positions = Position::with('employees')->orderBy('created_at', 'desc')->get();
        $organization = Position::tree();
        return view('admin.office.positions.index', compact('positions', 'organization'));
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
        foreach ($request->inputs as $value) {
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

    // Edit Info
    public function edit(Position $position)
    {
        $positions = Position::get();
        $places = Place::all();
        return view('admin.office.positions.edit', compact('position', 'positions', 'places'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Position $position)
    {
        $request->validate([
            'title'     => 'required|min:3|max:255',
            'position_number'   => 'required',
            'place_id'  => 'required',
            'desc'      => 'nullable'
        ]);

        $position->place_id     = $request->place_id;
        $position->parent_id    = $request->parent_id;
        $position->title        = $request->title;
        $position->position_number = $request->position_number;
        $position->num_of_pos   = $request->num_of_pos;
        $position->desc         = $request->desc;
        $position->save();

        activity('updated')
            ->causedBy(Auth::user())
            ->performedOn($position)
            ->log(trans('messages.positions.updatedPositionMsg'));

        $message = trans('messages.positions.updatedPositionMsg');
        return redirect()->route('admin.office.positions.show', $position->id)->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $position->delete();

        activity('deleted')
            ->causedBy(Auth::user())
            ->performedOn($position)
            ->log(trans('messages.positions.deletedPositionMsg'));

        $message = trans('messages.positions.deletedPositionMsg');
        return redirect()->route('admin.office.positions.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
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
