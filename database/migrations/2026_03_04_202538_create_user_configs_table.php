<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_config_id')->constrained('car_configs')->onDelete('cascade');
            $table->string('config_name', 100);
            $table->decimal('total_price', 12, 2);
            $table->string('selected_engine')->nullable();     
            $table->string('selected_color')->nullable();       
            $table->string('selected_interior')->nullable();    
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_configs');
    }
};