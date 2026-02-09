<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            if (!Schema::hasColumn('designs', 'photo_rotation')) {
                $table->decimal('photo_rotation', 6, 2)->default(0)->after('photo_height_pct');
            }
            if (!Schema::hasColumn('designs', 'name_x_pct')) {
                $table->decimal('name_x_pct', 5, 2)->default(50)->after('photo_rotation');
                $table->decimal('name_y_pct', 5, 2)->default(88)->after('name_x_pct');
                $table->decimal('name_width_pct', 5, 2)->default(80)->after('name_y_pct');
                $table->string('name_align', 10)->default('center')->after('name_width_pct');
                $table->unsignedSmallInteger('name_font_size')->default(18)->after('name_align');
            }
        });
    }

    public function down(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropColumn([
                'photo_rotation', 'name_x_pct', 'name_y_pct', 'name_width_pct', 'name_align', 'name_font_size'
            ]);
        });
    }
};
