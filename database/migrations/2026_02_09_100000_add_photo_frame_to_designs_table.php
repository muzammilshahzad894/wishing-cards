<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->decimal('photo_x_pct', 5, 2)->default(50)->after('name_placeholder')->comment('Center X of photo frame, % of card width');
            $table->decimal('photo_y_pct', 5, 2)->default(38)->after('photo_x_pct')->comment('Center Y of photo frame, % of card height');
            $table->decimal('photo_width_pct', 5, 2)->default(55)->after('photo_y_pct')->comment('Width of photo frame, % of card width');
            $table->decimal('photo_height_pct', 5, 2)->default(55)->after('photo_width_pct')->comment('Height of photo frame, % of card height');
            $table->decimal('photo_rotation', 6, 2)->default(0)->after('photo_height_pct')->comment('Rotation of photo frame in degrees');
            $table->decimal('name_x_pct', 5, 2)->default(50)->after('photo_rotation')->comment('Center X of name area, %');
            $table->decimal('name_y_pct', 5, 2)->default(88)->after('name_x_pct')->comment('Center Y of name area, %');
            $table->decimal('name_width_pct', 5, 2)->default(80)->after('name_y_pct')->comment('Max width of name area, %');
            $table->string('name_align', 10)->default('center')->after('name_width_pct')->comment('left, center, right');
            $table->unsignedSmallInteger('name_font_size')->default(18)->after('name_align')->comment('Font size in px');
        });
    }

    public function down(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropColumn([
                'photo_x_pct', 'photo_y_pct', 'photo_width_pct', 'photo_height_pct',
                'photo_rotation', 'name_x_pct', 'name_y_pct', 'name_width_pct', 'name_align', 'name_font_size'
            ]);
        });
    }
};
