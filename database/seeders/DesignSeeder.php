<?php

namespace Database\Seeders;

use App\Models\Design;
use Illuminate\Database\Seeder;

class DesignSeeder extends Seeder
{
    public function run(): void
    {
        $templatesByCategory = config('cards.templates', []);
        $order = 0;
        foreach ($templatesByCategory as $category => $templates) {
            foreach ($templates as $templateKey => $label) {
                Design::firstOrCreate(
                    [
                        'category' => $category,
                        'template_key' => $templateKey,
                    ],
                    [
                        'name' => $label,
                        'is_active' => true,
                        'order' => $order++,
                    ]
                );
            }
        }
    }
}
