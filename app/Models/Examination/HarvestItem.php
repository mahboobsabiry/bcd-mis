<?php

namespace App\Models\Examination;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HarvestItem extends Model
{
    use HasFactory;

    protected $fillable = ['harvest_id', 'good_name', 'hs_code', 'total_packages', 'weight', 'status', 'info'];

    // Belongs to Harvest
    public function harvest() : BelongsTo
    {
        return $this->belongsTo(Harvest::class);
    }
}
