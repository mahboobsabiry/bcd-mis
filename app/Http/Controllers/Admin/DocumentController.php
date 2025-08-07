<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\File;
use App\Models\Office\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:docs_view', ['only' => ['index', 'show']]);
        $this->middleware('permission:docs_create', ['only' => ['create','store']]);
        $this->middleware('permission:docs_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:docs_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $position = null;
            $documents = Document::all();
        } else {
            $position = Position::where('id', Auth::user()->employee->position_id)->firstOrFail();
            $documents = Auth::user()->employee->position->documents;
        }

        return view('admin.documents.index', compact('position', 'documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.documents.index')
                ->with(['message' => 'شما اجازه ارسال مکتوب را ندارید.', 'alertType' => 'secondary']);
        }
        $position = Position::where('id', Auth::user()->employee->position_id)->firstOrFail();
        return view('admin.documents.create', compact('position'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver'      => 'required',
            'cc'            => 'nullable',
            'subject'       => 'required',
            'doc_number'    => 'required',
            'doc_date'      => 'required',
            'appendices'    => 'required',
            'document'      => 'required'
        ]);

        $position = Position::where('id', Auth::user()->employee->position_id)->firstOrFail();

        $document           = new Document();
        $document->position_id = $position->id;
        $document->type     = $request->type;
        $document->receiver = $request->receiver;
        if ($request->cc) {
            $document->cc       = implode(', ', $request->cc);
        }
        $document->doc_type = $request->doc_type;
        $document->subject  = $request->subject;
        $document->doc_number   = $request->doc_number;
        $document->doc_date     = $request->doc_date;
        $document->appendices   = $request->appendices;
        $document->status   = 1;
        $document->info     = $request->info;
        $document->save();

        // Document
        if ($request->hasFile('document')) {
            // File
            // $file = $request->file('document');
            foreach ($request->file('document') as $item) {
                // New Document
                $f = new File();
                $fileName = 'doc-file-' . time() . rand(111, 99999) . '.' . $item->getClientOriginalExtension();
                $item->storeAs('documents/files', $fileName, 'public');
                $f->path   = $fileName;
                $document->files()->save($f);
            }
        }

        Session::put($document->subject, $document->id);

        return redirect()->route('admin.documents.show', $document->id)->with([
            'message'   => 'مکتوب موفقانه ذخیره گردید.',
            'alertType' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $document = Document::find($id);
        Session::forget($document->subject);
        $position = $document->position;
        return view('admin.documents.show', compact('document', 'position'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $document = Document::find($id);
        $position = $document->position;
        return view('admin.documents.edit', compact('document', 'position'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $document = Document::find($id);
        $request->validate([
            'subject'       => 'required',
            'doc_number'    => 'required',
            'doc_date'      => 'required',
            'appendices'    => 'required',
            'document'      => 'required'
        ]);

        $position = Position::where('id', Auth::user()->employee->position_id)->firstOrFail();

        $document->position_id = $position->id;
        $document->type     = $request->type;
        $document->doc_type = $request->doc_type;
        $document->subject  = $request->subject;
        $document->doc_number   = $request->doc_number;
        $document->doc_date     = $request->doc_date;
        $document->appendices   = $request->appendices;
        $document->status   = 1;
        $document->info     = $request->info;
        $document->save();

        if ($request->hasFile('document')) {
            // File
            // $file = $request->file('document');
            foreach ($request->file('document') as $item) {
                // New Document
                $f = new File();
                $fileName = 'doc-file-' . time() . '.' . $item->getClientOriginalExtension();
                $item->storeAs('documents/files', $fileName, 'public');
                $f->path   = $fileName;
                $document->files()->save($f);
            }
        }

        return redirect()->route('admin.documents.index')->with([
            'message'   => 'مکتوب موفقانه بروزرسانی گردید.',
            'alertType' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $document = Document::find($id);

        $document->delete();

        return redirect()->route('admin.documents.index')->with([
            'message'   => 'مکتوب موفقانه حذف گردید.',
            'alertType' => 'danger'
        ]);
    }

    // Received Documents
    public function received()
    {
        $position = Position::where('id', Auth::user()->employee->position_id)->firstOrFail();

        // Check if Authenticated Employee is On Duty or not
        if (Auth::user()->employee->on_duty == 1) {
            // Get all documents where receiver is employee or not
            $documents = Document::where('receiver', Auth::user()->employee->duty_position)->orWhere('cc', 'LIKE', '%' . Auth::user()->employee->duty_position . '%')->get();
        } else {
            $auth_user_pos = auth()->user()->employee->position;
            $documents = Document::where('receiver', $auth_user_pos->title)->orWhere('cc', 'LIKE', '%' . $auth_user_pos->title . '%')->get();
        }

        return view('admin.documents.received', compact('position', 'documents'));
    }
}
