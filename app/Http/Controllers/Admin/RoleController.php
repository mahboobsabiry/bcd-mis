<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user_mgmt', [
            'only' => ['index', 'create', 'store',  'edit', 'update', 'destroy']
        ]);
    }

    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact( 'permissions'));
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        $message = trans('messages.roles.addNewRoleMsg');
        return redirect()->route('admin.roles.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $role->load('permissions');
        return view('admin.roles.edit', compact('permissions', 'role'));
    }

    public function update(Request $request, Role $role)
    {
        // Validate
        $request->validate([
            'name'          => 'required|regex:/^[\pL\s\-]+$/u||min:3|max:48|unique:roles,name,' . $role->id,
            'permissions'   => 'required'
        ]);

        // Update Record
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        $message = trans('messages.roles.updateRoleMsg');
        return redirect()->route('admin.roles.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        $message = trans('messages.roles.deleteRoleMsg');
        return redirect()->route('admin.roles.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }
}
