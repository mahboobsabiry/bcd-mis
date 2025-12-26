<?php

namespace App\Models\Office;

use App\Models\Document;
use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'place_id', 'title',
        'position_number',
        'num_of_pos',
        'desc',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // In Position model, add these methods:

    /**
     * Get count of positions that are fully appointed
     */
    public static function getFullyAppointedCount(): int
    {
        return cache()->remember('positions_fully_appointed_count', 300, function () {
            return self::whereHas('codes', function($q) {
                $q->whereHas('employee');
            })
                ->withCount(['codes', 'codes as filled_codes_count' => function($q) {
                    $q->whereHas('employee');
                }])
                ->get()
                ->filter(function($position) {
                    return $position->filled_codes_count >= $position->num_of_pos;
                })
                ->count();
        });
    }

    /**
     * Get count of positions that are empty
     */
    public static function getEmptyPositionsCount(): int
    {
        return cache()->remember('positions_empty_count', 300, function () {
            // Positions with no codes
            $noCodes = self::doesntHave('codes')->count();

            // Positions with codes but all are empty
            $allCodesEmpty = self::whereHas('codes', function($q) {
                $q->whereDoesntHave('employee');
            })
                ->withCount(['codes', 'codes as empty_codes_count' => function($q) {
                    $q->whereDoesntHave('employee');
                }])
                ->get()
                ->filter(function($position) {
                    return $position->empty_codes_count >= $position->num_of_pos;
                })
                ->count();

            return $noCodes + $allCodesEmpty;
        });
    }

    /**
     * Get count of positions that need employees
     */
    public static function getVacantPositionsCount(): int
    {
        return cache()->remember('positions_vacant_count', 300, function () {
            return self::withCount(['codes', 'employees'])
                ->get()
                ->filter(function($position) {
                    $vacant = $position->num_of_pos - $position->codes_count;
                    return $vacant > 0;
                })
                ->count();
        });
    }

    /**
     * Get total number of codes
     */
    public static function getTotalCodesCount(): int
    {
        return cache()->remember('total_codes_count', 300, function () {
            return \App\Models\Office\PositionCode::count();
        });
    }

    /**
     * Get total filled codes count
     */
    public static function getFilledCodesCount(): int
    {
        return cache()->remember('filled_codes_count', 300, function () {
            return \App\Models\Office\PositionCode::whereHas('employee')->count();
        });
    }

    // Add these methods to your Position model:
    public function getCodesCountAttribute()
    {
        return $this->codes()->count();
    }

    public function getFilledCodesCountAttribute()
    {
        return $this->codes()->whereHas('employee')->count();
    }

    public function getEmptyCodesCountAttribute()
    {
        return $this->codes()->whereDoesntHave('employee')->count();
    }

    public function getVacantPositionsCountAttribute()
    {
        return max(0, $this->num_of_pos - $this->codes_count);
    }

    // Keep your original tree method - DON'T CHANGE THIS
    public static function tree()
    {
        $allPositions = Position::with('employees')->get();
        $rootPositions = $allPositions->where('parent_id', 0);

        self::formatTree($rootPositions, $allPositions);

        return $rootPositions;
    }

    // Keep your original formatTree method
    protected static function formatTree($positions, $allPositions)
    {
        foreach ($positions as $position) {
            $position->children = $allPositions->where('parent_id', $position->id)->values();

            if ($position->children->isNotEmpty()) {
                self::formatTree($position->children, $allPositions);
            }
        }
    }

    // Scopes for different position types
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    // Position with at least one code that has an employee
    public function scopeAppointed($query)
    {
        return $query->whereHas('codes', function ($q) {
            $q->whereHas('employee');
        });
    }

    // Position with codes but no employees assigned
    public function scopeEmpty($query)
    {
        return $query->whereHas('codes', function ($q) {
            $q->whereDoesntHave('employee');
        });
    }

    // Position with no codes created yet
    public function scopeUncoded($query)
    {
        return $query->whereDoesntHave('codes');
    }

    // Alternative tree method (optional, keeps your original tree method unchanged)
    public static function getTree()
    {
        return self::with(['children', 'codes.employee'])
            ->where('parent_id', 0)
            ->get();
    }

    // Relationships
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Position::class, 'parent_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function codes(): HasMany
    {
        return $this->hasMany(PositionCode::class);
    }

    // Helper methods
    public function getAppointedCodesCount(): int
    {
        return $this->codes()->whereHas('employee')->count();
    }

    public function getEmptyCodesCount(): int
    {
        return $this->codes()->whereDoesntHave('employee')->count();
    }

    public function getAllCodesCount(): int
    {
        return $this->codes()->count();
    }

    public function isFull(): bool
    {
        return $this->codes()->count() >= $this->num_of_pos;
    }

    public function getVacancies(): int
    {
        return max(0, $this->num_of_pos - $this->codes()->count());
    }
}
