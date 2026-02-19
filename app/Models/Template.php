<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    protected $fillable = [
        'title',
        'background_image',
    ];

    public function zones(): HasMany
    {
        return $this->hasMany(TemplateZone::class)->orderBy('id');
    }

    /**
     * Full URL to the background image (storage/app/public/templates/...).
     */
    public function getBackgroundImageUrlAttribute(): string
    {
        return asset('storage/' . $this->background_image);
    }
}
