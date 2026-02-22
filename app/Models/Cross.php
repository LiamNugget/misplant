<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Cross extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'slug',
        'mother_clone_id',
        'father_clone_id',
        'mother_name_text',
        'father_name_text',
        'cross_name',
        'is_op',
        'is_f2',
        'price',
        'seed_count',
        'seed_count_accuracy',
        'quantity_unit',
        'has_multiple_pricing',
        'all_prices_json',
        'initial_seed_count',
        'seeds_sold',
        'manual_adjustment',
        'status',
        'description',
    ];

    protected $with = ['mother', 'father'];

    protected $casts = [
        'price' => 'decimal:2',
        'seed_count' => 'integer',
        'initial_seed_count' => 'integer',
        'seeds_sold' => 'integer',
        'manual_adjustment' => 'integer',
        'is_op' => 'boolean',
        'is_f2' => 'boolean',
        'has_multiple_pricing' => 'boolean',
        'all_prices_json' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Cross $cross) {
            if (empty($cross->slug)) {
                $base = Str::slug($cross->code);
                $slug = $base;
                $i = 2;
                while (static::withoutGlobalScopes()->where('slug', $slug)->exists()) {
                    $slug = "{$base}-{$i}";
                    $i++;
                }
                $cross->slug = $slug;
            }
        });
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(CactusClone::class, 'mother_clone_id');
    }

    public function father(): BelongsTo
    {
        return $this->belongsTo(CactusClone::class, 'father_clone_id');
    }

    public function getDisplayNameAttribute(): string
    {
        $mother = $this->mother_name_text ?? $this->mother?->name ?? 'Unknown';
        $father = $this->father_name_text ?? $this->father?->name ?? 'Unknown';

        if ($this->is_op) {
            return "{$mother} OP";
        }

        return "{$mother} x {$father}";
    }

    public function getScarcityLevelAttribute(): string
    {
        if (in_array($this->status, ['coming_soon', 'new'])) {
            return 'coming_soon';
        }

        if ($this->status === 'sold_out') {
            return 'sold_out';
        }

        return match (true) {
            $this->seed_count <= 0 => 'sold_out',
            $this->seed_count <= 50 => 'critical',
            $this->seed_count <= 200 => 'low',
            $this->seed_count <= 500 => 'medium',
            default => 'plenty',
        };
    }

    public function getScarcityColorAttribute(): string
    {
        return match ($this->scarcity_level) {
            'coming_soon' => 'blue',
            'sold_out' => 'gray',
            'critical' => 'red',
            'low' => 'orange',
            'medium' => 'yellow',
            'plenty' => 'green',
        };
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeSoldOut($query)
    {
        return $query->where('status', 'sold_out');
    }

    public function scopeHotCrosses($query, int $threshold = 100)
    {
        return $query->where('status', 'available')
            ->where('seed_count', '>', 0)
            ->where('seed_count', '<=', $threshold)
            ->orderBy('seed_count');
    }

    public function scopeByPriceRange($query, float $min, float $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function relatedCrosses(int $limit = 6)
    {
        return static::where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->where('mother_clone_id', $this->mother_clone_id)
                    ->orWhere('father_clone_id', $this->father_clone_id)
                    ->orWhere('mother_clone_id', $this->father_clone_id)
                    ->orWhere('father_clone_id', $this->mother_clone_id);
            })
            ->limit($limit);
    }

    public function scopeLowStock($query, int $threshold = 200)
    {
        return $query->where('status', 'available')
            ->where('seed_count', '>', 0)
            ->where('seed_count', '<=', $threshold)
            ->orderBy('seed_count');
    }

    public function getEstimatedRemainingAttribute(): int
    {
        return $this->initial_seed_count - $this->seeds_sold + $this->manual_adjustment;
    }

    public function getFormattedRemainingAttribute(): string
    {
        $remaining = $this->estimated_remaining;
        $accuracy = $this->seed_count_accuracy;

        return match ($accuracy) {
            'exact' => (string) $remaining,
            'approximate' => "~{$remaining}",
            'estimated' => "~{$remaining} (est.)",
        };
    }
}
