<?php

namespace App\Http\Controllers\Admin\Office;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgentRequest;
use App\Models\Office\Agent;
use App\Models\Office\AgentColleague;
use App\Models\Office\Company;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:office_agent_view', ['only' => ['index', 'show', 'inactive']]);
        $this->middleware('permission:office_agent_create', ['only' => ['create','store', 'add_company', 'add_agent_company', 'add_colleague', 'add_agent_colleague']]);
        $this->middleware('permission:office_agent_edit', ['only' => ['edit','update', 'refresh_agent', 'refresh_colleague']]);
        $this->middleware('permission:office_agent_delete', ['only' => ['destroy']]);
    }

    // Fetch All Data
    public function index()
    {
        $agents = Agent::where('status', 1)->get();
        return view('admin.office.agents.index', compact('agents'));
    }

    // Create
    public function create()
    {
        return view('admin.office.agents.create');
    }

    // Store Data
    public function store(StoreAgentRequest $request)
    {
        $agent = new Agent();
        $agent->name    = $request->name;
        $agent->phone   = $request->phone;
        $agent->phone2  = $request->phone2;
        $agent->id_number  = $request->id_number;
        $agent->address = $request->address;
        $agent->info    = $request->info;

        //  Has File && Save Signature Scan
        if ($request->hasFile('signature')) {
            $avatar = $request->file('signature');
            $fileName = 'agent-signature-' . time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('agents/signatures', $fileName, 'public');
            $agent->signature        = $fileName;
        }
        $agent->save();

        //  Has File && Save Avatar Image
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'agent-' . time() . '.' . $avatar->getClientOriginalExtension();
            $agent->storeImage($avatar->storeAs('agents', $fileName, 'public'));
        }

        $message = 'ثبت شد!';
        return redirect()->route('admin.office.agents.show', $agent->id)->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Show
    public function show(Agent $agent)
    {
        return view('admin.office.agents.show', compact('agent'));
    }

    // Edit
    public function edit(Agent $agent)
    {
        return view('admin.office.agents.edit', compact('agent'));
    }

    // Update Data
    public function update(Request $request, Agent $agent)
    {
        // Validate
        $request->validate([
            'photo'     => 'nullable|image|mimes:jpg,png,jfif',
            'name'      => 'required|min:3|max:128',
            'phone'     => 'required|min:8|max:15|unique:agents,phone,' . $agent->id,
            'phone2'    => 'nullable|min:8|max:15',
            'id_number' => 'required|min:3|max:128',
            'address'   => 'nullable|min:3|max:128',
            'info'      => 'nullable'
        ]);

        // Save Record
        $agent->name    = $request->name;
        $agent->phone   = $request->phone;
        $agent->phone2  = $request->phone2;
        $agent->id_number  = $request->id_number;
        $agent->address = $request->address;
        $agent->info    = $request->info;

        //  Has File && Save Signature Scan
        if ($request->hasFile('signature')) {
            $avatar = $request->file('signature');
            $fileName = 'agent-signature-' . time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('agents/signatures', $fileName, 'public');
            $agent->signature        = $fileName;
        }
        $agent->save();

        //  Has Photo
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'agent-' . time() . '.' . $avatar->getClientOriginalExtension();
            $agent->updateImage($avatar->storeAs('agents', $fileName, 'public'));
        }

        $message = 'بروزرسانی گردید!';
        return redirect()->route('admin.office.agents.show', $agent->id)->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Delete Data
    public function destroy(Agent $agent)
    {
        $agent->delete();

        $message = 'حذف گردید!';
        return redirect()->route('admin.office.agents.index')->with([
            'message'   => $message,
            'alertType' => 'success'
        ]);
    }

    // Select Company
    public function select_company(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            // Query the database to get the relevant data
            $company = Company::find($data['company_id']);

            if ($company) {
                // Assuming 'employee_name' is the field you want to retrieve
                $company_name = $company->name;
                $company_tin = $company->tin;
                // Return the data as a JSON response
                return response()->json([
                    'company_name' => $company_name,
                    'tin' => $company_tin
                ]);
            } else {
                // Handle the case when the aircraft ID is not found
                return response()->json(['error' => 'Aircraft not found'], 404);
            }
        }
    }

    // Add Company
    public function add_company($id)
    {
        $agent = Agent::find($id);
        if (!empty($agent->company_name) && !empty($agent->company_name2) && !empty($agent->company_name3)) {
            return redirect()->back()->with([
                'message'   => 'یک شخص نماینده بیشتر از یک شرکت بوده نمی تواند.',
                'alertType' => 'danger'
            ]);
        }

        /** @var $companies
         * Send companies that doesn't have Agent and inactive
         */
        $companies = Company::all()->where('status', 0);

        return view('admin.office.agents.add_company', compact('agent', 'companies'));
    }

    // Add Agent Company
    public function add_agent_company(Request $request, $id)
    {
        $agent = Agent::find($id);
        $request->validate([
            'from_date'     => 'required',
            'to_date'       => 'required',
            'doc_number'    => 'required',
            'activity_sector'          => 'required',
            'company_name'  => 'required',
            'tin'           => 'required'
        ]);

        // Request Company
        $req_company = Company::where('id', $request->company_id)->first();

        // Get Saved Company and save
        $saved_company = Company::where('tin', $request->tin)->first();
        if (!empty($saved_company->agent_id)) {
            return back()->with(['message' => 'شرکت متذکره دارای نماینده میباشد.', 'alertType' => 'warning']);
        }

        /**
         * Save Company
         */
        if ($agent->company_name == null) {
            $agent->from_date   = $request->from_date;
            $agent->to_date     = $request->to_date;
            $agent->doc_number  = $request->doc_number;
            if ($req_company) {
                $agent->company_name    = $req_company->name;
                $agent->company_tin     = $req_company->tin;
            } else {
                $agent->company_name    = $request->company_name;
                $agent->company_tin     = $request->tin;
            }
        } elseif ($agent->company_name2 == null) {
            $agent->from_date2   = $request->from_date;
            $agent->to_date2     = $request->to_date;
            $agent->doc_number2  = $request->doc_number;
            if ($req_company) {
                $agent->company_name2   = $req_company->name;
                $agent->company_tin2    = $req_company->tin;
            } else {
                $agent->company_name2   = $request->company_name;
                $agent->company_tin2    = $request->tin;
            }
        } elseif ($agent->company_name3 == null) {
            $agent->from_date3   = $request->from_date;
            $agent->to_date3     = $request->to_date;
            $agent->doc_number3  = $request->doc_number;
            if ($req_company) {
                $agent->company_name3   = $req_company->name;
                $agent->company_tin3    = $req_company->tin;
            } else {
                $agent->company_name3   = $request->company_name;
                $agent->company_tin3    = $request->tin;
            }
        } elseif(!empty($agent->company_name) && !empty($agent->company_name2) && !empty($agent->company_name3)) {
            return redirect()->back()->with([
                'message'   => 'یک شخص نماینده بیشتر از یک شرکت بوده نمی تواند.',
                'alertType' => 'danger'
            ]);
        }
        $agent->status = 1;
        $agent->save();

        /**
         * Save company details
         */
        if ($saved_company) {
            $company = $saved_company;
            $company->agent_id  = $agent->id;
            $company->name      = $saved_company->name;
            $company->tin       = $saved_company->tin;
            $company->status    = 1;
        } else {
            $company = new Company();
            $company->agent_id  = $agent->id;
            if ($req_company) {
                $company->name      = $req_company->name;
                $company->tin       = $req_company->tin;
            } else {
                $company->name      = $request->company_name;
                $company->tin       = $request->tin;
            }
            $activity_sector = implode(',', $request->input('activity_sector'));
            $company->activity_sector = $activity_sector;
        }

        $company->save();

        return redirect()->route('admin.office.agents.show', $agent->id)->with([
            'message'   => 'شرکت موفقانه ثبت شد!',
            'alertType' => 'success'
        ]);
    }

    // Renewal Company
    public function renewal_company($id)
    {
        $company = Company::find($id);

        return view('admin.office.agents.renewal_company', compact('company'));
    }

    // Renewal Agent Company
    public function renewal_agent_company(Request $request, $id)
    {
        $company = Company::find($id);
        $request->validate([
            'from_date'     => 'required',
            'to_date'       => 'required',
            'doc_number'    => 'required'
        ]);

        /**
         * Save Company
         */
        $agent = Agent::where('id', $company->agent->id)->first();
        $company->update([
            'background'    => $company->background . 'از تاریخ ' . $company->agent->from_date . ' الی تاریخ ' . $company->agent->to_date. '  نظر به مکتوب نمبر ' . $company->agent->doc_number . '، ' . $company->agent->name . " را منحیث نماینده معرفی نمود.<br>",
        ]);
        $agent->update([
            'background'    => $agent->background . 'از تاریخ ' . $agent->from_date . ' الی تاریخ ' . $agent->to_date . ' منحیث نماینده شرکت ' . $agent->company_name . '  نظر به مکتوب نمبر ' . $agent->doc_number . " معرفی گردید.<br>"
        ]);

        if ($agent->company_name == $company->name) {
            $agent->from_date   = $request->from_date;
            $agent->to_date     = $request->to_date;
            $agent->doc_number  = $request->doc_number;
        } elseif ($agent->company_name2 == $company->name) {
            $agent->from_date2   = $request->from_date;
            $agent->to_date2     = $request->to_date;
            $agent->doc_number2  = $request->doc_number;
        } elseif ($agent->company_name3 == $company->name) {
            $agent->from_date3   = $request->from_date;
            $agent->to_date3     = $request->to_date;
            $agent->doc_number3  = $request->doc_number;
        }
        $agent->background = $agent->background . '<br>' . 'نمایندگی نماینده هذا به شرکت ' . $company->name . ' از تاریخ ' . $request->from_date . ' الی تاریخ ' . $request->to_date . ' بر اساس مکتوب نمبر ' . $request->doc_number . ' تمدید گردید.' . '<br>' . $request->info;
        $agent->save();

        // Update Company Background
        $company->update([
            'background' => $company->background . '<br>' . 'نمایندگی محترم ' . $agent->name . ' از تاریخ ' . $request->from_date . ' الی تاریخ ' . $request->to_date . ' بر اساس مکتوب نمبر ' . $request->doc_number . ' تمدید گردید.' . '<br>' . $request->info
        ]);

        return redirect()->route('admin.office.agents.show', $agent->id)->with([
            'message'   => ' موفقانه تمدید گردید!',
            'alertType' => 'success'
        ]);
    }

    // Refresh Agent
    public function refresh_agent($id)
    {
        $agent = Agent::find($id);
        // $company = Company::where('agent_id', $agent->id)->first();
        foreach ($agent->companies as $company) {
            // First Company
            if ($company->name == $agent->company_name) {
                $to_date = Jalalian::fromFormat('Y/m/d', $agent->to_date)->toCarbon();

                // Do Refresh When The Time Is Over
                if ($to_date < today()) {
                    $company->update([
                        'background'    => $company->background . 'از تاریخ ' . $company->agent->from_date . ' الی تاریخ ' . $company->agent->to_date. '  نظر به مکتوب نمبر ' . $company->agent->doc_number . '، ' . $company->agent->name . " را منحیث نماینده معرفی نمود.<br>",
                        'agent_id'      => null,
                        'status'        => 0
                    ]);

                    $agent->update([
                        'background'    => $agent->background . 'از تاریخ ' . $agent->from_date . ' الی تاریخ ' . $agent->to_date . ' منحیث نماینده شرکت ' . $agent->company_name . '  نظر به مکتوب نمبر ' . $agent->doc_number . " معرفی گردید.<br>",
                        'from_date'     => null,
                        'to_date'       => null,
                        'doc_number'    => null,
                        'company_name'  => null,
                        'company_tin'   => null
                    ]);
                }
            }

            // Second Company
            if ($company->name == $agent->company_name2) {
                $to_date = Jalalian::fromFormat('Y/m/d', $agent->to_date2)->toCarbon();

                // Do Refresh When The Time Is Over
                if ($to_date < today()) {
                    $company->update([
                        'background'    => $company->background . 'از تاریخ ' . $company->agent->from_date2 . ' الی تاریخ ' . $company->agent->to_date2. '  نظر به مکتوب نمبر ' . $company->agent->doc_number2 . '، ' . $company->agent->name . " را منحیث نماینده معرفی نمود.<br>",
                        'agent_id'      => null,
                        'status'        => 0
                    ]);

                    $agent->update([
                        'background'    => $agent->background . 'از تاریخ ' . $agent->from_date2 . ' الی تاریخ ' . $agent->to_date2 . ' منحیث نماینده شرکت ' . $agent->company_name2 . '  نظر به مکتوب نمبر ' . $agent->doc_number2 . " معرفی گردید.<br>",
                        'from_date2'     => null,
                        'to_date2'       => null,
                        'doc_number2'    => null,
                        'company_name2'  => null,
                        'company_tin2'   => null
                    ]);
                }
            }

            // Third Company
            if ($company->name == $agent->company_name3) {
                $to_date = Jalalian::fromFormat('Y/m/d', $agent->to_date3)->toCarbon();

                // Do Refresh When The Time Is Over
                if ($to_date < today()) {
                    $company->update([
                        'background'    => $company->background . 'از تاریخ ' . $company->agent->from_date3 . ' الی تاریخ ' . $company->agent->to_date3. '  نظر به مکتوب نمبر ' . $company->agent->doc_number3 . '، ' . $company->agent->name . " را منحیث نماینده معرفی نمود.<br>",
                        'agent_id'      => null,
                        'status'        => 0
                    ]);

                    $agent->update([
                        'background'    => $agent->background . 'از تاریخ ' . $agent->from_date3 . ' الی تاریخ ' . $agent->to_date3 . ' منحیث نماینده شرکت ' . $agent->company_name3 . '  نظر به مکتوب نمبر ' . $agent->doc_number3 . " معرفی گردید.<br>",
                        'from_date3'     => null,
                        'to_date3'       => null,
                        'doc_number3'    => null,
                        'company_name3'  => null,
                        'company_tin3'   => null
                    ]);
                }
            }
        }

        // If agent does not have any company then should be inactive
        if ($agent->company_name == null && $agent->company_name2 == null && $agent->company_name3 == null) {
            $agent->update([
                'status' => 0
            ]);
        }

        return redirect()->back()->with([
            'message'   => 'تازه سازی انجام شد!',
            'alertType' => 'success'
        ]);
    }

    // Refresh Agent Colleague
    public function refresh_colleague($id)
    {
        $agent = Agent::find($id);
        foreach ($agent->colleagues as $colleague) {
            $to_date = Jalalian::fromFormat('Y/m/d', $colleague->to_date)->toCarbon();

            // Do Refresh When The Time Is Over
            if ($to_date < today()) {
                $agent->update([
                    'background'    => $agent->background . 'از تاریخ <b> ' . $colleague->from_date . '</b> الی تاریخ <b> ' . $colleague->to_date . '</b>  نظر به مکتوب نمبر ' . $colleague->doc_number . ', <b>' . $colleague->name . "</b> را منحیث همکار با خود داشت.<br>"
                ]);

                $colleague->update([
                    'background'    => $colleague->background . 'از تاریخ <b> ' . $colleague->from_date . '</b> الی تاریخ <b> ' . $colleague->to_date. '</b>  نظر به مکتوب نمبر ' . $colleague->doc_number . '، منحیث همکار <b>' . $colleague->agent->name . "</b> معرفی گردید.<br>",
                    'agent_id'      => null,
                    'from_date'     => null,
                    'to_date'       => null,
                    'doc_number'    => null,
                    'status'        => 0
                ]);
            }
        }

        return redirect()->back()->with([
            'message'   => 'تازه سازی انجام شد!',
            'alertType' => 'success'
        ]);
    }

    // Inactive Agents
    public function inactive()
    {
        $agents = Agent::where('status', 0)->get();
        return view('admin.office.agents.inactive', compact('agents'));
    }

    // Add Colleague
    public function add_colleague($id)
    {
        $agent = Agent::find($id);
        return view('admin.office.agents.add_colleague', compact('agent'));
    }

    // Add Agent Colleague
    public function add_agent_colleague(Request $request, $id)
    {
        $agent = Agent::find($id);
        $request->validate([
            'name'          => 'required|min:3|max:128',
            'phone'         => 'required|min:8|max:15',
            'id_number'     => 'required|min:3|max:128',
            'address'       => 'required|min:3|max:128',
            'from_date'     => 'required',
            'to_date'       => 'required',
            'doc_number'    => 'required',
        ]);

        $colleague              = new AgentColleague();
        $colleague->agent_id    = $agent->id;
        $colleague->name        = $request->name;
        $colleague->phone       = $request->phone;
        $colleague->phone2      = $request->phone2;
        $colleague->id_number   = $request->id_number;
        $colleague->from_date   = $request->from_date;
        $colleague->to_date     = $request->to_date;
        $colleague->doc_number  = $request->doc_number;
        $colleague->address     = $request->address;
        $colleague->info        = $request->info;
        $colleague->status = 1;
        //  Has File && Save Signature Scan
        if ($request->hasFile('signature')) {
            $avatar = $request->file('signature');
            $fileName = 'agent-colleague-signature-' . time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('agent-colleagues/signatures', $fileName, 'public');
            $colleague->signature        = $fileName;
        }

        $colleague->save();

        //  Has File && Save Avatar Image
        if ($request->hasFile('photo')) {
            $avatar = $request->file('photo');
            $fileName = 'agent-colleague-' . time() . '.' . $avatar->getClientOriginalExtension();
            $colleague->storeImage($avatar->storeAs('agent-colleagues', $fileName, 'public'));
        }

        return redirect()->route('admin.office.agents.show', $agent->id)->with([
            'message'   => 'همکار موفقانه ثبت شد!',
            'alertType' => 'success'
        ]);
    }

    // Renewal Colleague
    public function renewal_colleague(Request $request, $id)
    {
        $colleague = AgentColleague::find($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'from_date'     => 'required',
                'to_date'       => 'required',
                'doc_number'    => 'required'
            ]);

            /**
             * Save Company
             */
            $agent = Agent::where('id', $colleague->agent->id)->first();

            $agent->update([
                'background'    => $agent->background . 'از تاریخ <b> ' . $colleague->from_date . '</b> الی تاریخ <b> ' . $colleague->to_date . '</b>  نظر به مکتوب نمبر ' . $colleague->doc_number . ', <b>' . $colleague->name . "</b> را منحیث همکار با خود داشت.<br>"
            ]);

            $colleague->update([
                'background'    => $colleague->background . 'از تاریخ <b> ' . $colleague->from_date . '</b> الی تاریخ <b> ' . $colleague->to_date. '</b>  نظر به مکتوب نمبر ' . $colleague->doc_number . '، منحیث همکار <b>' . $colleague->agent->name . "</b> معرفی گردید.<br>"
            ]);
            $colleague->from_date   = $request->from_date;
            $colleague->to_date     = $request->to_date;
            $colleague->doc_number  = $request->doc_number;

            $colleague->background = $colleague->background . '<br>' . ' از تاریخ ' . $request->from_date . ' الی تاریخ ' . $request->to_date . ' بر اساس مکتوب نمبر ' . $request->doc_number . ' تمدید گردید.' . '<br>' . $request->info;
            $colleague->save();

            // Update Company Background
            $agent->update([
                'background' => $agent->background . '<br>' . 'همکاری محترم ' . $colleague->name . ' از تاریخ ' . $request->from_date . ' الی تاریخ ' . $request->to_date . ' بر اساس مکتوب نمبر ' . $request->doc_number . ' تمدید گردید.' . '<br>' . $request->info
            ]);

            return redirect()->route('admin.office.agents.show', $agent->id)->with([
                'message'   => ' موفقانه تمدید گردید!',
                'alertType' => 'success'
            ]);
        }

        return view('admin.office.agents.renewal_colleague', compact('colleague'));
    }
}
