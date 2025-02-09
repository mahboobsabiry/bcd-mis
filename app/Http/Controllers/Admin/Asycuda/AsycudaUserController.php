<?php

namespace App\Http\Controllers\Admin\Asycuda;

use App\Http\Controllers\Controller;
use App\Models\Asycuda\AsycudaUser;
use App\Models\Asycuda\AsyUserResume;
use App\Models\Office\Employee;
use App\Models\Office\Resume;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsycudaUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:asy_user_view', ['only' => ['index', 'inactive', 'show']]);
        $this->middleware('permission:asy_user_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:asy_user_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:asy_user_delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $asycuda_users = AsycudaUser::all()->where('status', 1);
        return view('admin.asycuda.users.index', compact('asycuda_users'));
    }

    public function inactive()
    {
        $asycuda_users = AsycudaUser::all()->where('status', 0);
        return view('admin.asycuda.users.inactive', compact('asycuda_users'));
    }

    // Create
    public function create()
    {
        $employees = Employee::doesntHave('asycuda_user')->where('status', 0)->get();
        $places = Place::all()->where('status', 1)->where('custom_code', '!=', null);
        return view('admin.asycuda.users.create', compact('employees', 'places'));
    }

    // Select Employee
    public function select_employee(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            // Query the database to get the relevant data
            $employee = Employee::find($data['employee_id']);

            if ($employee) {
                $employee_emp_number = $employee->emp_number;

                // Return the data as a JSON response
                return response()->json([
                    'employee_emp_number' => $employee_emp_number
                ]);
            } else {
                // Handle the case when the aircraft ID is not found
                return response()->json(['error' => 'Aircraft not found'], 404);
            }
        }
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'employee_id'   => 'required|unique:asycuda_users,employee_id',
            'user'          => 'required|unique:asycuda_users,user',
            'password'      => 'required',
            'roles'         => 'required'
        ]);

        $user = AsycudaUser::create($request->all());

        return redirect()->route('admin.asycuda.users.show', $user->id)->with([
            'message'   => 'یوزر اسیکودا ثبت شد',
            'alertType' => 'success'
        ]);
    }

    // Show
    public function show($id)
    {
        $asycuda_user = AsycudaUser::findOrFail($id);
        return view('admin.asycuda.users.show', compact('asycuda_user'));
    }

    // Edit
    public function edit($id)
    {
        $asycuda_user = AsycudaUser::findOrFail($id);
        $places = Place::all()->where('status', 1)->where('custom_code', '!=', null);
        return view('admin.asycuda.users.edit', compact('asycuda_user', 'places'));
    }

    // Store
    public function update(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required',
            'info'  => 'nullable'
        ]);

        $user = AsycudaUser::find($id);

        $user->update($request->all());

        return redirect()->route('admin.asycuda.users.show', $user->id)->with([
            'message'   => 'یوزر اسیکودا بروزرسانی شد',
            'alertType' => 'success'
        ]);
    }

    // Delete
    public function destroy($id)
    {
        $user = AsycudaUser::find($id);

        $user->delete();

        return redirect()->route('admin.asycuda.users.index')->with([
            'message'   => 'یوزر اسیکودا حذف گردید',
            'alertType' => 'success'
        ]);
    }

    // Update Status
    public function updateAsyUserStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            $asycuda_user = AsycudaUser::where('id', $data['asy_user_id'])->first();
            $asycuda_user->update(['status' => $status]);
            $exp = Resume::where('employee_id', $asycuda_user->employee->id)->latest()->first();

            if ($exp) {
                if ($data['status'] == 'Active') {
                    $exp->update(['asy_user_status' => $status, 'asy_user_roles' => null]);
                } else {
                    $exp->update(['asy_user_status' => $status, 'asy_user_roles' => $asycuda_user->roles]);
                }
            }

            return response()->json(['status' => $status]);
        }
    }

    // Add Asycuda User Resume
    public function add_user_resume($id)
    {
        $asycuda_user = AsycudaUser::find($id);
        return view('admin.asycuda.users.add_user_resume', compact('asycuda_user'));
    }

    // Store Asycuda User Resume
    public function store_user_resume(Request $request, $id)
    {
        $request->validate([
            'position'  => 'required',
            'position_type' => 'required',
            'user_roles'    => 'required',
            'user_status'   => 'required',
            'doc_number'    => 'nullable',
            'doc_date'      => 'nullable',
            'username'      => 'required',
            'password'      => 'required',
            'info'          => 'nullable'
        ]);

        $asycuda_user = AsycudaUser::find($id);

        $resume = new AsyUserResume();
        $resume->asy_user_id   = $asycuda_user->id;
        $resume->position      = $request->position;
        $resume->position_type = $request->position_type;
        $resume->doc_number    = $request->doc_number;
        $resume->doc_date      = $request->doc_date;
        $resume->username      = $request->username;
        $resume->password      = $request->password;
        $resume->user_status   = $request->user_status;
        $resume->user_roles    = $request->user_roles;
        $resume->info          = $request->info;
        $resume->save();

        $asycuda_user->update(['user' => $request->username, 'password' => $request->password, 'roles' => $request->user_roles, 'status' => $request->user_status]);

        //  Has File && Save Avatar Image
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'asy-user-doc-' . time() . '.' . $avatar->getClientOriginalExtension();
            $resume->storeImage($avatar->storeAs('asycuda/users/docs', $fileName, 'public'));
        }

        return redirect()->route('admin.asycuda.users.show', $asycuda_user->id)->with([
            'message'   => 'موفقانه ثبت گردید!',
            'alertType' => 'success'
        ]);
    }

    // User Form
    public function user_form($id)
    {
        $asycuda_user = AsycudaUser::find($id);
        return view('admin.asycuda.users.user_form', compact('asycuda_user'));
    }
}
