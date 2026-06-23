<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('car_configs', function (Blueprint $table) {
            $table->id();
            $table->string('car_key', 20)->unique();
            $table->string('name', 100);
            $table->decimal('base_price', 12, 2);
            $table->string('variant', 50)->nullable();
            $table->text('description')->nullable();
            $table->integer('year');
            $table->json('config_data')->nullable(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('car_configs');
    }
};