<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('auction_cars')->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->bigInteger('starting_price');
            $table->bigInteger('reserve_price')->default(0);
            $table->decimal('current_bid', 15, 2)->default(0); 
            $table->integer('bid_count')->default(0);  
            $table->string('winner_name')->nullable();
            $table->string('winner_email')->nullable();
            $table->bigInteger('final_price')->nullable();
            $table->text('winner_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auctions');
    }
};