<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_config_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['main_image', 'interior_image', 'model_3d']);
            $table->string('file_path');
            $table->string('interior_key')->nullable();
            $table->integer('sort_order')->nullable()->default(0);
            $table->string('title')->nullable();
            $table->timestamps();

            $table->index(['car_config_id', 'type']);
            $table->index(['car_config_id', 'interior_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_media');
    }
};