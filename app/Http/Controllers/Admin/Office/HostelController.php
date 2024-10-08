<?php

namespace App\Http\Controllers\Admin\Office;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHostelRequest;
use App\Models\Office\Hostel;
use App\Models\Place;
use Illuminate\Http\Request;

class HostelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:office_hostel_view', ['only' => ['index', 'show']]);
        $this->middleware('permission:office_hostel_create', ['only' => ['create','store']]);
        $this->middleware('permission:office_hostel_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:office_hostel_delete', ['only' => ['destroy']]);
    }

    // Fetch All Data
    public function index()
    {
        $hostels = Hostel::all();
        $places = Place::with('hostels')->whereHas('hostels')->get();
        return view('admin.office.hostel.index', compact('hostels', 'places'));
    }

    // Create
    public function create()
    {
        $places = Place::all();
        return view('admin.office.hostel.create', compact('places'));
    }

    // Store Data
    public function store(StoreHostelRequest $request)
    {
        Hostel::create($request->all());

        $message = 'ثبت شد!';
        return redirect()->route('admin.office.hostel.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Show
    public function show(Hostel $hostel)
    {
        return view('admin.office.hostel.show', compact('hostel'));
    }

    // Edit
    public function edit(Hostel $hostel)
    {
        $places = Place::all();
        return view('admin.office.hostel.edit', compact('hostel', 'places'));
    }

    // Update Data
    public function update(Request $request, Hostel $hostel)
    {
        // Validate
        $request->validate([
            'number'    => 'required',
            'section'   => 'nullable',
            'capacity'  => 'nullable',
            'info'      => 'nullable'
        ]);

        // Save Record
        $hostel->update($request->all());

        $message = 'بروزرسانی گردید!';
        return redirect()->route('admin.office.hostel.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Delete Data
    public function destroy(Hostel $hostel)
    {
        $hostel->delete();

        $message = 'حذف گردید!';
        return redirect()->route('admin.office.hostel.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }
}
