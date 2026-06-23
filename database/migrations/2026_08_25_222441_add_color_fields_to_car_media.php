<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_media', function (Blueprint $table) {
            $table->string('color_key')->nullable()->after('interior_key');
        });

        DB::statement("ALTER TABLE car_media MODIFY type ENUM('main_image', 'interior_image', 'model_3d', 'color_image') NOT NULL");
    }

    public function down(): void
    {
        Schema::table('car_media', function (Blueprint $table) {
            $table->dropColumn('color_key');
        });

        DB::statement("ALTER TABLE car_media MODIFY type ENUM('main_image', 'interior_image', 'model_3d') NOT NULL");
    }
};