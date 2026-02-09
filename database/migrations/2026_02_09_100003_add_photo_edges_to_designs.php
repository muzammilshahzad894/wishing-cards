<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            if (!Schema::hasColumn('designs', 'photo_left_pct')) {
                $table->decimal('photo_left_pct', 5, 2)->nullable()->after('photo_shape');
                $table->decimal('photo_top_pct', 5, 2)->nullable()->after('photo_left_pct');
                $table->decimal('photo_right_pct', 5, 2)->nullable()->after('photo_top_pct');
                $table->decimal('photo_bottom_pct', 5, 2)->nullable()->after('photo_right_pct');
            }
        });

        // Backfill from center+size for existing rows
        $rows = \DB::table('designs')->get();
        foreach ($rows as $r) {
            $x = (float) ($r->photo_x_pct ?? 50);
            $y = (float) ($r->photo_y_pct ?? 38);
            $w = (float) ($r->photo_width_pct ?? 55);
            $h = (float) ($r->photo_height_pct ?? 55);
            \DB::table('designs')->where('id', $r->id)->update([
                'photo_left_pct' => $x - $w / 2,
                'photo_top_pct' => $y - $h / 2,
                'photo_right_pct' => $x + $w / 2,
                'photo_bottom_pct' => $y + $h / 2,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropColumn(['photo_left_pct', 'photo_top_pct', 'photo_right_pct', 'photo_bottom_pct']);
        });
    }
};
