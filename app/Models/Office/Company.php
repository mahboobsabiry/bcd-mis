<?php

namespace App\Models\Office;

use App\Models\Examination\PreferentialTariff;
use App\Models\Warehouse\Assurance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['agent_id', 'name', 'tin', 'activity_sector', 'address', 'owner_name', 'deputy_name', 'owner_id_card', 'owner_phone', 'owner_main_add', 'owner_cur_add', 'background', 'info', 'status', 'created_at', 'updated_at'];

    public function agent(): Relation
    {
        return $this->belongsTo(Agent::class);
    }

    // Has Many Assurance
    public function assurances() : HasMany
    {
        return $this->hasMany(Assurance::class);
    }

    // Has Many Properties
    public function pt() : HasMany
    {
        return $this->hasMany(PreferentialTariff::class, 'company_id', 'id');
    }
}
