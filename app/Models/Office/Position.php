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
    public function getFilledCodesCountAttribute()
    {
        if (!$this->relationLoaded('codes')) {
            return 0;
        }

        return $this->codes->where('employee', '!=', null)->count();
    }

    // Scopes
    /**
     * Scope for fully filled positions (all position slots filled with employees)
     */
    public function scopeFullyFilled($query)
    {
        return $query->whereHas('codes', function($q) {
            $q->whereHas('employee');
        })
            ->withCount(['codes', 'codes as filled_codes_count' => function($q) {
                $q->whereHas('employee');
            }])
            ->get() // Execute query to get collection
            ->filter(function($position) {
                return $position->filled_codes_count >= $position->num_of_pos;
            })
            ->values(); // Reset keys
    }

    /**
     * Scope for positions with vacancies
     * Simplified version without HAVING clause
     */
    public function scopeWithVacancies($query)
    {
        return $query->where(function($q) {
            // Positions with less codes than required positions
            $q->whereRaw('num_of_pos > (
            SELECT COUNT(*) FROM position_codes
            WHERE position_codes.position_id = positions.id
        )')
                // OR positions where some codes don't have employees
                ->orWhereExists(function($subQuery) {
                    $subQuery->select(\DB::raw(1))
                        ->from('position_codes')
                        ->whereColumn('position_codes.position_id', 'positions.id')
                        ->whereNotExists(function($q) {
                            $q->select(\DB::raw(1))
                                ->from('employees')
                                ->whereColumn('employees.ps_code_id', 'position_codes.id');
                        });
                });
        });
    }

    /**
     * Scope for positions with no codes
     */
    public function scopeUncoded($query)
    {
        return $query->whereDoesntHave('codes');
    }

    /**
     * Scope for positions with all codes empty
     */
    public function scopeAllCodesEmpty($query)
    {
        return $query->whereHas('codes', function($q) {
            $q->whereDoesntHave('employee');
        })->whereDoesntHave('codes', function($q) {
            $q->whereHas('employee');
        });
    }

    /**
     * Scope for positions with at least one filled code
     */
    public function scopeHasFilledCodes($query)
    {
        return $query->whereHas('codes', function($q) {
            $q->whereHas('employee');
        });
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
