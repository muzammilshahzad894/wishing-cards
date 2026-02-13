<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable = [
        'name',
        'template_key',
        'is_active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Default data for template (greeting, placeholder name).
     */
    public function getTemplateDefaults(): array
    {
        $defaults = config('cards.template_defaults', []);
        $key = $this->template_key;
        return [
            'greetingText' => $defaults[$key]['greeting'] ?? 'Happy Birthday',
            'namePlaceholder' => $defaults[$key]['name_placeholder'] ?? 'Your Name',
        ];
    }
}
