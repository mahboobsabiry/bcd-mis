<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user_mgmt', ['only' => ['index','store', 'update', 'destroy']]);
    }

    // Fetch All Data
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    // Store Data
    public function store(PermissionRequest $request)
    {
        $perm = Permission::create($request->all());

        $message = trans('messages.permissions.addNewPermMsg');
        return redirect()->route('admin.permissions.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Update Data
    public function update(Request $request, Permission $permission)
    {
        // Validate
        $request->validate([
            'name' => 'required|min:3|max:48|unique:permissions,name,'.$permission->id,
        ]);

        // Save Record
        $permission->update($request->all());

        $message = trans('messages.permissions.updatePermMsg');
        return redirect()->route('admin.permissions.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Delete Data
    public function destroy(Permission $permission)
    {
        $permission->delete();

        $message = trans('messages.permissions.deletePermMsg');
        return redirect()->route('admin.permissions.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }
}
