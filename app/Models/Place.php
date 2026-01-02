<?php

namespace App\Models;

use App\Models\Asycuda\AsycudaUser;
use App\Models\Office\Hostel;
use App\Models\Office\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'custom_code', 'status', 'info'];

    // Cast status to boolean
    protected $casts = [
        'status' => 'boolean',
    ];

    // Users
    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }

    // Asycuda Users
    public function asycuda_users() : HasMany
    {
        return $this->hasMany(AsycudaUser::class);
    }

    // Positions
    public function positions() : HasMany
    {
        return $this->hasMany(Position::class);
    }

    // Hostels
    public function hostels() : HasMany
    {
        return $this->hasMany(Hostel::class);
    }

    // Custom attribute: total position codes count
    public function getPositionsCodesCountAttribute()
    {
        if (!$this->relationLoaded('positions')) {
            return 0;
        }

        return $this->positions->sum(function($position) {
            if ($position->relationLoaded('codes')) {
                return $position->codes->count();
            }
            return $position->codes()->count();
        });
    }

    // Custom attribute: occupied positions count
    public function getOccupiedPositionsCountAttribute()
    {
        if (!$this->relationLoaded('positions')) {
            return 0;
        }

        return $this->positions->sum(function($position) {
            if ($position->relationLoaded('codes')) {
                return $position->codes->whereHas('employee')->count();
            }
            return $position->codes()->whereHas('employee')->count();
        });
    }

    // Custom attribute: vacant positions count
    public function getVacantPositionsCountAttribute()
    {
        if (!$this->relationLoaded('positions')) {
            return 0;
        }

        return $this->positions->sum(function($position) {
            if ($position->relationLoaded('codes')) {
                return $position->codes->whereDoesntHave('employee')->count();
            }
            return $position->codes()->whereDoesntHave('employee')->count();
        });
    }

    // Scope: Active places
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope: Inactive places
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    // Scope: With positions
    public function scopeWithPositions($query)
    {
        return $query->whereHas('positions');
    }

    // Scope: Without positions
    public function scopeWithoutPositions($query)
    {
        return $query->whereDoesntHave('positions');
    }

    // Helper method: Get status badge
    public function getStatusBadgeAttribute()
    {
        if ($this->status) {
            return '<span class="badge badge-success">فعال</span>';
        }
        return '<span class="badge badge-secondary">غیرفعال</span>';
    }

    // Helper method: Get status text
    public function getStatusTextAttribute()
    {
        return $this->status ? 'فعال' : 'غیرفعال';
    }
}
