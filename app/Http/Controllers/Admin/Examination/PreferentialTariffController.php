<?php

namespace App\Http\Controllers\Admin\Examination;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferentialTariffRequest;
use App\Models\Examination\PreferentialTariff;
use App\Models\Office\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferentialTariffController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:examination_pt_view', ['only' => ['index', 'show']]);
        $this->middleware('permission:examination_pt_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:examination_pt_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:examination_pt_delete', ['only' => ['destroy']]);
    }

    /**
     * Index Page to retrieve all of preferential_tariffs
     */
    public function index()
    {
        $tariffs = PreferentialTariff::all();

        return view('admin.examination.preferential_tariffs.index', compact('tariffs'));
    }

    /**
     * Create Page
     */
    public function create()
    {
        $companies = Company::all()->where('status', 1);
        return view('admin.examination.preferential_tariffs.create', compact('companies'));
    }

    /**
     * Store DATA
     */
    public function store(PreferentialTariffRequest $request)
    {
        $tariff               = new PreferentialTariff();
        $tariff->user_id      = Auth::user()->id;
        $tariff->company_id   = $request->company_id;
        $tariff->doc_number   = $request->doc_number;
        $tariff->doc_date     = $request->doc_date;
        $tariff->start_date   = $request->start_date;
        $tariff->end_date     = $request->end_date;
        $tariff->info         = $request->info;
        $tariff->save();

        //  Has File && Save Avatar Image
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'property-' . time() . rand(111, 99999) . '.' . $avatar->getClientOriginalExtension();
            $tariff->storeImage($avatar->storeAs('examination/preferential_tariffs', $fileName, 'public'));
        }

        return redirect()->route('admin.examination.preferential_tariffs.index')->with([
            'message'   => 'جایداد ثبت گردید!',
            'alertType' => 'success'
        ]);
    }

    /**
     * Show details of record
     */
    public function show(PreferentialTariff $tariff)
    {
        return view('admin.examination.preferential_tariffs.show', compact('tariff'));
    }

    /**
     * Edit details of record
     */
    public function edit(PreferentialTariff $tariff)
    {
        $companies = Company::all()->where('status', 1);
        return view('admin.examination.preferential_tariffs.edit', compact('tariff', 'companies'));
    }

    /**
     * Update DATA
     */
    public function update(Request $request, PreferentialTariff $tariff)
    {
        $tariff->user_id      = Auth::user()->id;
        $tariff->company_id   = $request->company_id;
        $tariff->doc_number   = $request->doc_number;
        $tariff->doc_date     = $request->doc_date;
        $tariff->start_date   = $request->start_date;
        $tariff->end_date     = $request->end_date;
        $tariff->info         = $request->info;
        $tariff->save();

        //  Has File && Save Avatar Image
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'property-' . time() . rand(111, 99999) . '.' . $avatar->getClientOriginalExtension();
            $tariff->updateImage($avatar->storeAs('examination/preferential_tariffs', $fileName, 'public'));
        }

        return redirect()->route('admin.examination.preferential_tariffs.index')->with([
            'message'   => 'موفقانه بروزرسانی گردید!',
            'alertType' => 'success'
        ]);
    }

    /**
     * Delete DATA
     */
    public function destroy(PreferentialTariff $tariff)
    {
        $tariff->delete();

        return redirect()->route('admin.examination.preferential_tariffs.index')->with([
            'message'   => 'موفقانه حذف گردید!',
            'alertType' => 'success'
        ]);
    }
}
