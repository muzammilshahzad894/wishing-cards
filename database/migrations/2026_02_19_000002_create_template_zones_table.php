<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // 'photo' or 'text'
            $table->string('shape', 20); // 'rect', 'circle', 'oval'
            $table->decimal('x', 12, 4)->default(0);
            $table->decimal('y', 12, 4)->default(0);
            $table->decimal('width', 12, 4)->default(0);
            $table->decimal('height', 12, 4)->default(0);
            $table->json('meta')->nullable(); // fontSize, color, fieldName (e.g. "name")
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_zones');
    }
};
