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
     * Category folder name (e.g. "birthday-cards" → "birthday").
     */
    public function getCategoryFolder(): string
    {
        return explode('-', $this->category)[0] ?? $this->category;
    }

    /**
     * Template folder name (e.g. "birthday-classic" with category "birthday-cards" → "classic").
     */
    public function getTemplateFolder(): string
    {
        $prefix = $this->getCategoryFolder() . '-';
        return str_starts_with($this->template_key, $prefix)
            ? substr($this->template_key, strlen($prefix))
            : $this->template_key;
    }

    /**
     * View path: cards.templates.{category}.{template}.template
     * e.g. cards.templates.birthday.classic.template
     */
    public function getTemplateViewPath(): string
    {
        return 'cards.templates.' . $this->getCategoryFolder() . '.' . $this->getTemplateFolder() . '.template';
    }

    /**
     * URL to this template's CSS: frontend/css/templates/{category}/{template}.css
     */
    public function getTemplateCssUrl(): string
    {
        return asset('frontend/css/templates/' . $this->getCategoryFolder() . '/' . $this->getTemplateFolder() . '.css');
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
