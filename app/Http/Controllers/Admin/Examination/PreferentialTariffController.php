<?php

namespace App\Http\Controllers\Admin\Examination;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferentialTariffRequest;
use App\Models\Examination\PreferentialTariff;
use App\Models\Examination\PTItems;
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
        $tariffs = PreferentialTariff::all()->where('status', 0);

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

        $data = $request->all();
        // Add Product Attributes
        foreach ($data['good_name'] as $key => $value) {
            if (!empty($value)) {
                // Add Attribute
                $item = new PTItems();
                $item->pt_id        = $tariff->id;
                $item->good_name    = $value;
                $item->hs_code      = $data['hs_code'][$key];
                $item->total_packages   = $data['total_packages'][$key];
                $item->weight           = $data['weight'][$key];
                $item->save();
            }
        }

        //  Has File && Save Avatar Image
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'pt-' . time() . rand(111, 99999) . '.' . $avatar->getClientOriginalExtension();
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
    public function show($id)
    {
        $tariff = PreferentialTariff::with('pt_items')->where('id', $id)->get();
        return view('admin.examination.preferential_tariffs.show', compact('tariff'));
    }

    /**
     * Edit details of record
     */
    public function edit($id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);
        $companies = Company::all()->where('status', 1);
        return view('admin.examination.preferential_tariffs.edit', compact('tariff', 'companies'));
    }

    /**
     * Update DATA
     */
    public function update(Request $request, $id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);
        $tariff->user_id      = Auth::user()->id;
        $tariff->company_id   = $request->company_id;
        $tariff->doc_number   = $request->doc_number;
        $tariff->doc_date     = $request->doc_date;
        $tariff->start_date   = $request->start_date;
        $tariff->end_date     = $request->end_date;
        $tariff->info         = $tariff->info . '<br>' . $request->info;
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
    public function destroy($id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);
        $tariff->delete();

        return redirect()->route('admin.examination.preferential_tariffs.index')->with([
            'message'   => 'موفقانه حذف گردید!',
            'alertType' => 'success'
        ]);
    }

    // Renewal
    public function renewal(Request $request, $id)
    {
        $tariff = PreferentialTariff::with('pt_items')->findOrFail($id);

        if ($request->isMethod('POST')) {
            // Validation
            $request->validate([
                'end_date'      => 'required',
                'doc_number'    => 'required',
                'doc_date'      => 'required'
            ]);

            $tariff->update([
                'end_date'  => $request->end_date,
                'info'      => $tariff->info . '<br> الی تاریخ ' . $request->end_date . ' بر اساس مکتوب نمبر ' . $request->doc_number . ' مورخ ' . $request->doc_date . ' تمدید گردید.'
            ]);

            return redirect()->route('admin.examination.preferential_tariffs.show', $tariff->id)->with([
                'message'   => 'موفقانه تمدید گردید.',
                'alertType' => 'success'
            ]);
        }

        return view('admin.examination.preferential_tariffs.renewal', compact('tariff'));
    }

    // Harvesting PT
    public function harvesting_pts()
    {
        $tariffs = PreferentialTariff::all()->where('status', 1);

        return view('admin.examination.preferential_tariffs.harvesting_pts', compact('tariffs'));
    }

    // Harvested PT
    public function harvested_pts()
    {
        $tariffs = PreferentialTariff::all()->where('status', 2);

        return view('admin.examination.preferential_tariffs.harvested_pts', compact('tariffs'));
    }
}
