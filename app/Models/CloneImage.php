<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CloneImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'cactus_clone_id',
        'image_url',
        'filename',
        'alt_text',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function cactusClone(): BelongsTo
    {
        return $this->belongsTo(CactusClone::class);
    }
}
