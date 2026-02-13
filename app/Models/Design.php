<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable = [
        'name',
        'category',
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

    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * View path for template: cards.templates.{category}.{template_key}
     */
    public function getTemplateViewPath(): string
    {
        return 'cards.templates.' . $this->category . '.' . $this->template_key;
    }

    /**
     * Display label for category (e.g. "Birthday Cards" from "birthday-cards").
     */
    public function getCategoryLabel(): string
    {
        $categories = config('cards.categories', []);
        return $categories[$this->category] ?? ucwords(str_replace('-', ' ', $this->category));
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
