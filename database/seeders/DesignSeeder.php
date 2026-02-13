<?php

namespace Database\Seeders;

use App\Models\Design;
use Illuminate\Database\Seeder;

class DesignSeeder extends Seeder
{
    public function run(): void
    {
        $templates = config('cards.templates', []);
        $order = 0;
        foreach ($templates as $key => $label) {
            Design::firstOrCreate(
                ['template_key' => $key],
                [
                    'name' => $label,
                    'is_active' => true,
                    'order' => $order++,
                ]
            );
        }
    }
}
