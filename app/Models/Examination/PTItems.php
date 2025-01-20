<?php

namespace App\Models\Examination;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class PTItems extends Model
{
    use HasFactory;

    public $table = 'pt_items';

    protected $fillable = [
        'pt_id', 'good_name', 'hs_code', 'total_packages', 'weight', 'status', 'info'
    ];

    // Preferential Tariffs
    public function pt() : Relation
    {
        return $this->belongsTo(PreferentialTariff::class, 'pt_id');
    }
}
