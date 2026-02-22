<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class CactusClone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'species',
        'description',
        'detail_url',
        'main_image_url',
        'is_active',
        'is_monstrose',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_monstrose' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (CactusClone $clone) {
            if (empty($clone->slug)) {
                $clone->slug = Str::slug($clone->name);
            }
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(CloneImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(CloneImage::class)->where('is_primary', true);
    }

    public function crossesAsMother(): HasMany
    {
        return $this->hasMany(Cross::class, 'mother_clone_id');
    }

    public function crossesAsFather(): HasMany
    {
        return $this->hasMany(Cross::class, 'father_clone_id');
    }

    public function allCrosses()
    {
        return Cross::where(function ($query) {
            $query->where('mother_clone_id', $this->id)
                ->orWhere('father_clone_id', $this->id);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySpecies($query, string $species)
    {
        return $query->where('species', $species);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'clone_tag', 'cactus_clone_id', 'tag_id');
    }

    public function scopeWithTag($query, string $tagSlug)
    {
        return $query->whereHas('tags', function ($q) use ($tagSlug) {
            $q->where('slug', $tagSlug);
        });
    }
}
