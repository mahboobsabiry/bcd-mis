<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Asycuda\COAL;
use App\Models\Examination\Property;
use App\Models\Office\Employee;
use App\Models\Warehouse\Assurance;
use App\Traits\HasPhoto;
use App\Traits\HasTazkira;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPhoto;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'name',
        'username',
        'phone',
        'email',
        'password',
        'is_admin',
        'place',
        'status',
        'info'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Morph Photo
    public function photo(): MorphOne
    {
        return $this->morphOne(Photo::class, 'transaction');
    }

    // Activities
    public function activities()
    {
        return Activity::all()->where('causer_id', $this->id);
    }

    // Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Has Companies Activity License
    public function coal()
    {
        return $this->hasMany(COAL::class);
    }

    // Admin
    public function isAdmin()
    {
        return $this->is_admin == 0;
    }

    // Employee
    public function isEmployee()
    {
        return $this->is_admin == 1;
    }

    // Head - Riyasat
    public function head()
    {
        return $this->place == 0;
    }

    // Border
    public function border()
    {
        return $this->place == 1;
    }

    // Airport
    public function airport()
    {
        return $this->place == 2;
    }

    // Nayeb ABAD
    public function nAbad()
    {
        return $this->place == 3;
    }

    // Muraqeban Sayar
    public function mSayar()
    {
        return $this->place == 4;
    }

    // Has Many Assurance
    public function assurances() : HasMany
    {
        return $this->hasMany(Assurance::class);
    }

    // Has Many Properties
    public function properties() : HasMany
    {
        return $this->hasMany(Property::class);
    }
}
