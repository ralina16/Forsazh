<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 100);
            $table->string('model', 100);
            $table->string('photo')->nullable();
            $table->string('catalog_photo')->nullable();
            $table->string('drive', 50);
            $table->decimal('engine', 4, 1)->nullable();
            $table->string('fuel', 50);
            $table->integer('mileage')->nullable();
            $table->string('condition', 50);
            $table->integer('owners')->nullable();
            $table->integer('transmissions')->nullable();
            $table->integer('trunk')->nullable();
            $table->string('gearbox', 50);
            $table->string('body', 50);
            $table->bigInteger('price');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
