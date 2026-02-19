<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateZone extends Model
{
    protected $fillable = [
        'template_id',
        'type',
        'shape',
        'x',
        'y',
        'width',
        'height',
        'angle',
        'scale_x',
        'scale_y',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'x' => 'float',
            'y' => 'float',
            'width' => 'float',
            'height' => 'float',
            'angle' => 'float',
            'scale_x' => 'float',
            'scale_y' => 'float',
            'meta' => 'array',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get field name for text zones (e.g. "name", "message").
     */
    public function getFieldNameAttribute(): ?string
    {
        return $this->meta['fieldName'] ?? null;
    }
}
