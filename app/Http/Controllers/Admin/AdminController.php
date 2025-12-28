<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office\Employee;
use App\Models\Office\Position;
use App\Models\Office\PositionCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:site_admin', [
            'only' => ['activities', 'deleteActivity', 'deleteAllActivities',  'deleteAllAdminActivities']
        ]);
    }

    public function dashboard()
    {
         $top_users = User::all()->take(5);
         $logActivities = Activity::orderBy('created_at', 'desc')->take(5)->get();

        $appointment_positions = PositionCode::whereHas('employee')->count();
        $empty_positions = PositionCode::whereDoesntHave('employee')->count();

         return view('admin.dashboard', compact('logActivities', 'top_users', 'appointment_positions', 'empty_positions'));
    }

    public function activities()
    {
        $logActivities = Activity::orderBy('created_at', 'desc')->get();
        $myActivities = Activity::orderBy('created_at', 'desc')->causedBy(Auth::user())->get();

        return view('admin.activities', compact('logActivities', 'myActivities'));
    }

    public function deleteActivity($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->back()->with([
            'message'   => trans('messages.users.deleteActivityMsg'),
            'alertType' => 'danger'
        ]);
    }

    // Delete All Activities
    public function deleteAllActivities()
    {
        $activities = Activity::all();

        foreach ($activities as $activity) {
            $activity->delete();
        }

        return redirect()->back()->with([
            'message'   => trans('messages.users.deleteAllActivitiesMsg'),
            'alertType' => 'danger'
        ]);
    }

    // Delete All Activities
    public function deleteAllAdminActivities()
    {
        $activities = Activity::all()->where('causer_id', auth()->user()->id);

        foreach ($activities as $activity) {
            $activity->delete();
        }

        return redirect()->back()->with([
            'message'   => trans('messages.users.deleteAllActivitiesMsg'),
            'alertType' => 'danger'
        ]);
    }

    // Logout
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    // Profile
    public function profile()
    {
        $user = Auth::user();

        return view('admin.profile', compact('user'));
    }

    // Edit Profile
    public function editProfile()
    {
        $user = Auth::user();

        return view('admin.edit-profile', compact('user'));
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar'    => 'image|mimes:jpg,png',
            'name'      => 'required|min:2|max:64',
            'phone'     => 'nullable|min:8|max:15|unique:users,phone,'.$user->id,
            'email'     => 'required|min:8|max:128|unique:users,email,'.$user->id,
            'info'      => 'nullable'
        ]);

        $user->update($request->all());
        //  Has File
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $fileName = 'user-' . time() . '.' . $avatar->getClientOriginalExtension();
            $user->updateImage($avatar->storeAs('users', $fileName, 'public'));
        }

        $message = trans('messages.users.updateUserMsg');

        return redirect()->route('admin.profile')->with('success', $message);
    }

    // Check admin current password if ok or not
    public function checkCurrentPwd(Request $request)
    {
        $data = $request->all();

        if (Hash::check($data['cur_password'], Auth::user()->password)) {
            echo "true";
        } else {
            echo "false";
        }
    }

    // Update current password
    public function updatePassword(Request $request)
    {
        $data = $request->all();

        // Check if current password is correct
        if (Hash::check($data['cur_password'], Auth::user()->password)) {
            //Check if new and confirm new password is matching
            if ($data['new_password'] == $data['confirm_password']) {
                User::where('id', Auth::user()->id)
                    ->update(['password' => Hash::make($data['new_password'])]);

                return response()->json(['message' => trans('pages.profile.updPwdMsg')]);
            } else {
                return response()->json(['error' => trans('pages.profile.updPwdErrMsg')]);
            }
        } else {
            return response()->json(['incorrect' => trans('pages.profile.invalidCurPwd')]);
        }
    }
}
