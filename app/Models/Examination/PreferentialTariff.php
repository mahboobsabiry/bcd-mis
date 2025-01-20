<?php

namespace App\Models\Examination;

use App\Models\Office\Company;
use App\Models\Office\Employee;
use App\Models\Photo;
use App\Models\User;
use App\Traits\HasPhoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Relation;

class PreferentialTariff extends Model
{
    use HasFactory, HasPhoto;

    protected $fillable = ['user_id', 'company_id', 'doc_number', 'doc_date', 'start_date', 'end_date', 'status', 'info'];

    // Morph Photo
    public function photo(): MorphOne
    {
        return $this->morphOne(Photo::class, 'transaction');
    }

    /**
     * Belongs to User Model
     */
    public function user() : Relation
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs to Employee Model
     */
    public function company() : Relation
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    // Has Items
    public function pt_items() : HasMany
    {
        return $this->hasMany(PTItems::class, 'pt_id', 'id');
    }
}
