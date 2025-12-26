<?php

namespace App\Exports;

use App\Models\Office\Position;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PositionsExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Apply the same filtering logic as in controller
        $query = Position::with(['codes.employee', 'parent', 'place']);

        if ($this->request->filled('status')) {
            $status = $this->request->get('status');
            if ($status === 'filled') {
                $query->whereHas('codes.employee');
            } elseif ($status === 'empty') {
                $query->whereDoesntHave('codes.employee');
            }
        }

        if ($this->request->filled('level')) {
            $query->where('position_number', $this->request->get('level'));
        }

        $positions = $query->get();

        return $positions->map(function ($position) {
            $filledCodes = $position->codes->filter(fn($code) => $code->employee)->count();
            $totalCodes = $position->codes->count();
            $emptyCodes = $totalCodes - $filledCodes;

            return [
                $position->id,
                $position->title,
                $position->parent->title ?? 'ریاست',
                $filledCodes,
                $emptyCodes,
                $position->position_number,
                $position->place->name ?? '',
                $position->desc ?? ''
            ];
        });
    }

    public function headings(): array
    {
        // Dynamic headers based on language
        $lang = $this->request->get('lang', 'fa');

        if ($lang === 'ps') {
            return ['آی ډي', 'د دندې سرلیک', 'مشر دفتر', 'پر کوډونه', 'خالي کوډونه', 'درجه', 'ځای', 'تفصیلات'];
        } elseif ($lang === 'en') {
            return ['ID', 'Position Title', 'Parent Position', 'Filled Codes', 'Empty Codes', 'Grade', 'Location', 'Description'];
        }

        // Default Persian
        return ['شناسه', 'عنوان بست', 'بست مافوق', 'کدهای پر', 'کدهای خالی', 'درجه', 'موقعیت', 'توضیحات'];
    }
}
