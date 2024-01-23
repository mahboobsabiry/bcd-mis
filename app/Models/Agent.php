<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'phone2', 'address', 'background', 'info',
        'from_date', 'to_date', 'doc_number', 'company_name', 'company_tin',
        'from_date2', 'to_date2', 'doc_number2', 'company_name2', 'company_tin2',
        'from_date3', 'to_date3', 'doc_number3', 'company_name3', 'company_tin3',
        'status'
    ];

    // Has Many Companies
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}
