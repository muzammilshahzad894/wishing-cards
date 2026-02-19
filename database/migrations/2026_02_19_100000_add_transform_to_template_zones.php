<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_zones', function (Blueprint $table) {
            $table->decimal('angle', 8, 2)->default(0)->after('height');
            $table->decimal('scale_x', 10, 4)->default(1)->after('angle');
            $table->decimal('scale_y', 10, 4)->default(1)->after('scale_x');
        });
    }

    public function down(): void
    {
        Schema::table('template_zones', function (Blueprint $table) {
            $table->dropColumn(['angle', 'scale_x', 'scale_y']);
        });
    }
};
