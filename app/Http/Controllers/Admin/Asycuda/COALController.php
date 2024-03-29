<?php

namespace App\Http\Controllers\Admin\Asycuda;

use App\Http\Controllers\Controller;
use App\Http\Requests\COALRequest;
use App\Models\Asycuda\COAL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Spatie\Browsershot\Browsershot;

// Companies Activity License Controller
class COALController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:asy_coal_view', ['only' => ['index', 'expired', 'show']]);
        $this->middleware('permission:asy_coal_create', ['only' => ['create','store', 'reg_form', 'refresh']]);
        $this->middleware('permission:asy_coal_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:asy_coal_delete', ['only' => ['destroy']]);
    }

    // Index
    public function index()
    {
        $coal = COAL::all()->where('status', 1);
        return view('admin.asycuda.coal.index', compact('coal'));
    }

    public function expired()
    {
        $coal = COAL::all()->where('status', 0);
        return view('admin.asycuda.coal.expired', compact('coal'));
    }

    // Create
    public function create()
    {
        return view('admin.asycuda.coal.create');
    }

    // Store
    public function store(COALRequest $request)
    {
        $cal = new COAL();
        $cal->user_id           = Auth::user()->id;
        $cal->company_name      = $request->company_name;
        $cal->company_tin       = $request->company_tin;
        $cal->license_number    = $request->license_number;

        // Convert Jalali to Georgian & Save to the DB
        $export_date = Jalalian::fromFormat('Y/m/d', $request->export_date)->toCarbon();
        $cal->export_date       = $export_date;
        $expire_date = Jalalian::fromFormat('Y/m/d', $request->expire_date)->toCarbon();
        $cal->expire_date   = $expire_date;

        $cal->owner_name    = $request->owner_name;
        $cal->owner_phone   = $request->owner_phone;
        $cal->phone         = $request->phone;
        $cal->email         = $request->email;
        $cal->address       = $request->address;
        $cal->info          = $request->info;
        $cal->save();

        return redirect()->route('admin.asycuda.coal.show', $cal->id)->with([
            'message'   => 'جواز فعالیت شرکت موفقانه ثبت گردید!',
            'alertType' => 'success'
        ]);
    }

    // Show
    public function show($id)
    {
        $cal = COAL::find($id);
        return view('admin.asycuda.coal.show', compact('cal'));
    }

    // Reg Form
    public function reg_form($id)
    {
        $cal = COAL::find($id);
        return view('admin.asycuda.coal.reg_form', compact('cal'));
    }

    // Edit
    public function edit($id)
    {
        $cal = COAL::find($id);
        return view('admin.asycuda.coal.edit', compact('cal'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $cal = COAL::find($id);

        $request->validate([
            'company_name'  => 'required|unique:coal,company_name,' . $cal->id,
            'company_tin'   => 'required|unique:coal,company_tin,' . $cal->id,
            'license_number'=> 'required|unique:coal,license_number,' . $cal->id,
            'export_date'   => 'required',
            'expire_date'   => 'required',
            'owner_name'    => 'required|min:3|max:128',
            'owner_phone'   => 'nullable|min:8|max:15|unique:coal,owner_phone,' . $cal->id,
            'phone'         => 'nullable|min:8|max:15|unique:coal,phone,' . $cal->id,
            'email'         => 'nullable|min:5|max:128|unique:coal,email,' . $cal->id,
            'address'       => 'required|min:3|max:255',
            'info'          => 'nullable'
        ]);

        $cal->user_id           = Auth::user()->id;
        $cal->company_name      = $request->company_name;
        $cal->company_tin       = $request->company_tin;
        $cal->license_number    = $request->license_number;

        // Convert Jalali to Georgian & Save to the DB
        $export_date = Jalalian::fromFormat('Y/m/d', $request->export_date)->toCarbon();
        $cal->export_date       = $export_date;
        $expire_date = Jalalian::fromFormat('Y/m/d', $request->expire_date)->toCarbon();
        $cal->expire_date   = $expire_date;

        $cal->owner_name    = $request->owner_name;
        $cal->owner_phone   = $request->owner_phone;
        $cal->phone         = $request->phone;
        $cal->email         = $request->email;
        $cal->address       = $request->address;
        $cal->info          = $request->info;
        $cal->save();

        return redirect()->route('admin.asycuda.coal.show', $cal->id)->with([
            'message'   => 'جواز فعالیت شرکت موفقانه ویرایش گردید!',
            'alertType' => 'success'
        ]);
    }

    // Delete
    public function destroy($id)
    {
        $cal = COAL::find($id);
        $cal->delete();

        return redirect()->route('admin.asycuda.coal.index')->with([
            'message'   => 'جواز فعالیت شرکت حذف گردید!',
            'alertType' => 'danger'
        ]);
    }

    // Refresh Company Activity License
    public function refresh($id)
    {
        $cal = COAL::find($id);

        if ($cal->expire_date < today()) {
            $cal->update([
                'status'    => 0,
                'info'      => 'تاریخ جواز ختم گردیده است.'
            ]);
        }

        return redirect()->back()->with([
            'message'   => 'تازه سازی انجام شد.',
            'alertType' => 'success'
        ]);
    }

    // COAL Print Form
    public function coal_print_form($id)
    {
        $cal = COAL::find($id);
        $path = public_path('/assets/files/coal/');

        Browsershot::url('https://www.google.com')->save('google.pdf');
        // Browsershot::url(route('admin.asycuda.coal.print.form', $cal->id))->save($path . 'فورمه جواز شرکت ' . $cal->company_name . '.pdf');
        return back()->with([
            'success'   => 'ذخیره شد',
            'alertType' => 'success'
        ]);
    }

    // Upload Cal Form
    public function upload_cal(Request $request, $id)
    {
        $cal = COAL::findOrFail($id);

        $request->validate([
            'cal'   => 'required'
        ]);

        //  Has Photo
        if ($request->hasFile('cal')) {
            $avatar = $request->file('cal');
            $fileName = 'cal-' . time() . '.' . $avatar->getClientOriginalExtension();
            if ($cal->photo) {
                $cal->updateImage($avatar->storeAs('coal', $fileName, 'public'));
            } else {
                $cal->storeImage($avatar->storeAs('coal', $fileName, 'public'));
            }
        }

        return redirect()->back()->with([
            'message'   => 'جواز فعالیت شرکت آپلود شد.',
            'alertType' => 'success'
        ]);
    }
}
