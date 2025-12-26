<?php

namespace App\Models\Office;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PositionCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id', 'code', 'status', 'info'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeEmpty($query)
    {
        return $query->whereDoesntHave('employee');
    }

    public function scopeOccupied($query)
    {
        return $query->whereHas('employee');
    }

    // Relationships
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'ps_code_id');
    }

    // Helper methods
    public function isOccupied(): bool
    {
        return $this->employee()->exists();
    }

    public function getStatusText(): string
    {
        if ($this->status == 0) return 'غیرفعال';
        return $this->isOccupied() ? 'اشغال شده' : 'خالی';
    }

    public function getStatusBadge(): string
    {
        if ($this->status == 0) return '<span class="badge badge-secondary">غیرفعال</span>';

        return $this->isOccupied()
            ? '<span class="badge badge-success">اشغال شده</span>'
            : '<span class="badge badge-warning">خالی</span>';
    }
}
