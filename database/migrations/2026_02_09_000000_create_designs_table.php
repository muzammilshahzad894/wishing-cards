<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image'); // path to uploaded design image
            $table->string('greeting_text')->nullable();
            $table->string('name_placeholder')->default('Any Name Here');
            $table->decimal('photo_x_pct', 5, 2)->default(50)->comment('Center X of photo frame, % of card width');
            $table->decimal('photo_y_pct', 5, 2)->default(38)->comment('Center Y of photo frame, % of card height');
            $table->decimal('photo_width_pct', 5, 2)->default(55)->comment('Width of photo frame, % of card width');
            $table->decimal('photo_height_pct', 5, 2)->default(55)->comment('Height of photo frame, % of card height');
            $table->decimal('photo_rotation', 6, 2)->default(0)->comment('Rotation of photo frame in degrees');
            $table->string('photo_shape', 20)->default('rectangle');
            $table->decimal('name_x_pct', 5, 2)->default(50)->comment('Center X of name area, %');
            $table->decimal('name_y_pct', 5, 2)->default(88)->comment('Center Y of name area, %');
            $table->decimal('name_width_pct', 5, 2)->default(80)->comment('Max width of name area, %');
            $table->string('name_align', 10)->default('center')->comment('left, center, right');
            $table->unsignedSmallInteger('name_font_size')->default(18)->comment('Font size in px');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
