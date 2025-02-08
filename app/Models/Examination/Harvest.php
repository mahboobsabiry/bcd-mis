<?php

namespace App\Models\Examination;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Harvest extends Model
{
    use HasFactory;

    protected $fillable = ['pt_id', 'code', 'weight', 'status', 'info'];

    // Belongs to Preferential Tariff
    public function pt() : BelongsTo
    {
        return $this->belongsTo(PreferentialTariff::class, 'pt_id', 'id');
    }

    // Has Many Items
    public function items() : HasMany
    {
        return $this->hasMany(HarvestItem::class);
    }
}
