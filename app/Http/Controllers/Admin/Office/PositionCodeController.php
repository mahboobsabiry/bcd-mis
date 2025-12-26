<?php

namespace App\Http\Controllers\Admin\Office;

use App\Http\Controllers\Controller;
use App\Models\Office\Position;
use App\Models\Office\PositionCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PositionCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:office_position_create', ['only' => ['store']]);
        $this->middleware('permission:office_position_edit', ['only' => ['update']]);
        $this->middleware('permission:office_position_delete', ['only' => ['destroy']]);
    }

    /**
     * Store a newly created position code.
     */
    public function store(Request $request, Position $position)
    {
        // Check if position has capacity for new codes
        if ($position->codes()->count() >= $position->num_of_pos) {
            return response()->json([
                'success' => false,
                'message' => 'ظرفیت کدهای این بست تکمیل شده است.'
            ], 422);
        }

        $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('position_codes')->where(function ($query) use ($position) {
                    return $query->where('position_id', $position->id);
                })
            ],
            'status' => 'required|boolean',
            'info' => 'nullable|string|max:500'
        ], [
            'code.required' => 'کد الزامی است',
            'code.unique' => 'این کد قبلاً برای این بست ثبت شده است',
            'code.max' => 'کد نمی‌تواند بیشتر از ۲۰ کاراکتر باشد'
        ]);

        try {
            $code = new PositionCode();
            $code->position_id = $position->id;
            $code->code = $request->code;
            $code->status = $request->status;
            $code->info = $request->info;
            $code->save();

            // Log activity
            activity('created')
                ->causedBy(Auth::user())
                ->performedOn($code)
                ->withProperties([
                    'position_id' => $position->id,
                    'position_title' => $position->title
                ])
                ->log('کد جدید برای بست ایجاد شد');

            return back()->with([
                'message'   => 'کد با موفقیت ایجاد شد.',
                'alertType' => 'success'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to create position code: ' . $e->getMessage());

            return back()->with([
                'message'   => 'خطا در ایجاد کد. لطفاً دوباره تلاش کنید.',
                'alertType' => 'danger'
            ]);
        }
    }
}
