<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:employee_mgmt', [
            'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'updateEmployeeStatus']
        ]);
    }

    // Index
    public function index()
    {
        $employees = Employee::orderBy('created_at', 'desc')->get();

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $positions = Position::tree();
        return view('admin.employees.create', compact('positions'));
    }

    // Store Record
    public function store(StoreEmployeeRequest $request)
    {
        $position = Position::where('id', $request->position_id)->first();
        if ($position->employees()->count() >= $position->num_of_pos) {
            return back()->with([
                'alertType' => 'danger',
                'message'   => 'بست مورد نظر تکمیل میباشد.'
            ]);
        }
        $employee = new Employee();
        $employee->position_id  = $request->position_id;
        $employee->name         = $request->name;
        $employee->last_name    = $request->last_name;
        $employee->father_name  = $request->father_name;
        $employee->gender       = $request->gender;
        $employee->emp_number   = $request->emp_number;
        $employee->appointment_number   = $request->appointment_number;
        $employee->appointment_date     = $request->appointment_date;
        $employee->last_duty        = $request->last_duty;
        $employee->birth_year       = $request->birth_year;
        $employee->education        = $request->education;
        $employee->prr_npr          = $request->prr_npr;
        $employee->prr_date         = $request->prr_date;
        $employee->phone            = $request->phone;
        $employee->phone2           = $request->phone2;
        $employee->email            = $request->email;
        $employee->main_province    = $request->main_province;
        $employee->main_district    = $request->main_district;
        $employee->current_province = $request->current_province;
        $employee->current_district = $request->current_district;
        $employee->introducer       = $request->introducer;
        $employee->info             = $request->info;
        // Save On Duty
        if ($request->has('on_duty')) {
            $on_duty = 0;
        } else {
            $on_duty = 1;
        }
        $employee->on_duty          = $on_duty;
        $employee->duty_position    = $request->duty_position;
        $employee->save();

        //  Has File && Save Avatar Image
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'employee-' . time() . '.' . $avatar->getClientOriginalExtension();
            $employee->storeImage($avatar->storeAs('employees', $fileName, 'public'));
        }

        //  Has File && Save Tazkira Image
        if ($request->hasFile('tazkira')) {
            $tazkira = $request->file('tazkira');
            $fileName = 'employeeIdCard-' . time() . '.' . $tazkira->getClientOriginalExtension();
            $employee->storeTaz($tazkira->storeAs('employees/tazkiras', $fileName, 'public'));
        }

        activity('added')
            ->causedBy(Auth::user())
            ->performedOn($employee)
            ->log(trans('messages.employees.addNewEmployeeMsg'));

        $message = trans('messages.employees.addNewEmployeeMsg');
        return redirect()->route('admin.employees.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Show Info
    public function show(Employee $employee)
    {
        $admin = Auth::user()->roles->first()->name == 'Admin';
        return view('admin.employees.show', compact('employee', 'admin'));
    }

    // Edit Info
    public function edit(Employee $employee)
    {
        $positions = Position::tree();
        return view('admin.employees.edit', compact('employee', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'photo'         => 'image|mimes:jpg,png,jfif',
            'name'          => 'required|min:3|max:64',
            'last_name'     => 'required|min:3|max:64',
            'father_name'   => 'required|min:3|max:64',
            'gender'        => 'required',
            'emp_number'    => 'required|unique:employees,emp_number,' . $employee->id,
            'appointment_number'    => 'required|unique:employees,appointment_number,' . $employee->id,
            'appointment_date'      => 'nullable',
            'last_duty'     => 'nullable',
            'birth_year'    => 'required',
            'education'     => 'nullable',
            'prr_npr'       => 'required',
            'prr_date'      => 'nullable',
            'phone'         => 'nullable|unique:employees,phone,' . $employee->id,
            'phone2'        => 'nullable|unique:employees,phone2,' . $employee->id,
            'email'         => 'nullable|unique:employees,email,' . $employee->id,
            'main_province'     => 'required|min:3|max:64',
            'main_district'     => 'required|min:3|max:64',
            'current_province'  => 'required|min:3|max:64',
            'current_district'  => 'required|min:3|max:64',
            'introducer'        => 'nullable|min:3|max:64',
            'info'              => 'nullable',
        ]);

        $position = Position::where('id', $request->position_id)->first();
        if ($position->employees()->count() > $position->num_of_pos) {
            return back()->with([
                'alertType' => 'danger',
                'message'   => 'بست مورد نظر تکمیل میباشد.'
            ]);
        }

        $employee->position_id  = $request->position_id;
        $employee->name         = $request->name;
        $employee->last_name    = $request->last_name;
        $employee->father_name  = $request->father_name;
        $employee->gender       = $request->gender;
        $employee->emp_number   = $request->emp_number;
        $employee->appointment_number    = $request->appointment_number;
        $employee->appointment_date      = $request->appointment_date;
        $employee->last_duty        = $request->last_duty;
        $employee->birth_year       = $request->birth_year;
        $employee->education        = $request->education;
        $employee->prr_npr          = $request->prr_npr;
        $employee->prr_date         = $request->prr_date;
        $employee->phone            = $request->phone;
        $employee->phone2           = $request->phone2;
        $employee->email            = $request->email;
        $employee->main_province    = $request->main_province;
        $employee->main_district    = $request->main_district;
        $employee->current_province = $request->current_province;
        $employee->current_district = $request->current_district;
        $employee->introducer       = $request->introducer;
        $employee->info             = $request->info;
        // Save On Duty
        if ($request->has('on_duty')) {
            $on_duty = 0;
        } else {
            $on_duty = 1;
        }
        $employee->on_duty          = $on_duty;
        $employee->duty_position    = $request->duty_position;
        $employee->save();

        //  Has File
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'employee-' . time() . '.' . $avatar->getClientOriginalExtension();
            $employee->updateImage($avatar->storeAs('employees', $fileName, 'public'));
        }

        //  Has Tazkira
        if ($request->hasFile('tazkira')) {
            $tazkira = $request->file('tazkira');
            $fileName = 'employeeIdCard-' . time() . '.' . $tazkira->getClientOriginalExtension();
            $employee->updateTaz($tazkira->storeAs('employees/tazkiras', $fileName, 'public'));
        }

        activity('updated')
            ->causedBy(Auth::user())
            ->log(trans('messages.employees.updateEmployeeMsg'));

        $message = trans('messages.employees.updateEmployeeMsg');

        return redirect()->route('admin.employees.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Delete Employee
    public function destroy(Employee $employee)
    {
        $employee->delete();

        activity('deleted')
            ->causedBy(Auth::user())
            ->performedOn($employee)
            ->log(trans('messages.employees.deleteEmployeeMsg'));

        $message = trans('messages.employees.deleteEmployeeMsg');

        return redirect()->route('admin.employees.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Update Status
    public function updateEmployeeStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Employee::where('id', $data['employee_id'])->update(['status' => $status]);
            return response()->json(['status' => $status, 'employee_id' => $data['employee_id']]);
        }
    }

    // Main Position Employees
    public function main_employees()
    {
        $employees = Employee::where('on_duty', 0)->orderBy('created_at', 'desc')->get();

        return view('admin.employees.main', compact('employees'));
    }

    // On Duty Employees
    public function on_duty_employees()
    {
        $employees = Employee::where('on_duty', 1)->orderBy('created_at', 'desc')->get();

        return view('admin.employees.on_duty', compact('employees'));
    }
}
