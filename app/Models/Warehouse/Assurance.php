<?php

namespace App\Models\Warehouse;

use App\Models\Office\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Assurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_id', 'good_name', 'assurance_total',
        'inquiry_number', 'inquiry_date',
        'bank_tt_number', 'bank_tt_date',
        'expire_date',
        'status', 'reason',
        'created_at', 'updated_at'
    ];

    public function user() : Relation
    {
        return $this->belongsTo(User::class);
    }

    public function company() : Relation
    {
        return $this->belongsTo(Company::class);
    }
}
