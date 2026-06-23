<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('auction_cars', function (Blueprint $table) {
            $table->id();
            $table->string('model', 100);
            $table->string('photo')->nullable(); 
            $table->json('additional_photos')->nullable();
            $table->string('drive', 50);
            $table->string('engine', 50);
            $table->string('fuel', 50);
            $table->string('mileage', 50); 
            $table->string('condition', 50);
            $table->integer('owners')->nullable();
            $table->integer('transmissions')->nullable();
            $table->string('trunk', 50);
            $table->string('gearbox', 50);
            $table->string('body', 50);
            $table->bigInteger('price');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auction_cars');
    }
};