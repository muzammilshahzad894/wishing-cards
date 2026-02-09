<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable = [
        'name',
        'image',
        'greeting_text',
        'name_placeholder',
        'photo_x_pct',
        'photo_y_pct',
        'photo_width_pct',
        'photo_height_pct',
        'photo_rotation',
        'photo_shape',
        'photo_left_pct',
        'photo_top_pct',
        'photo_right_pct',
        'photo_bottom_pct',
        'name_x_pct',
        'name_y_pct',
        'name_width_pct',
        'name_align',
        'name_font_size',
        'is_active',
    ];

    protected $attributes = [
        'photo_x_pct' => 50,
        'photo_y_pct' => 38,
        'photo_width_pct' => 55,
        'photo_height_pct' => 55,
        'photo_rotation' => 0,
        'photo_shape' => 'rectangle',
        'name_x_pct' => 50,
        'name_y_pct' => 88,
        'name_width_pct' => 80,
        'name_align' => 'center',
        'name_font_size' => 18,
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'photo_x_pct' => 'float',
            'photo_y_pct' => 'float',
            'photo_width_pct' => 'float',
            'photo_height_pct' => 'float',
            'photo_rotation' => 'float',
            'photo_left_pct' => 'float',
            'photo_top_pct' => 'float',
            'photo_right_pct' => 'float',
            'photo_bottom_pct' => 'float',
            'name_x_pct' => 'float',
            'name_y_pct' => 'float',
            'name_width_pct' => 'float',
            'name_font_size' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
