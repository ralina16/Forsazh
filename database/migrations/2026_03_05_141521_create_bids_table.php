<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->bigInteger('amount');
            $table->boolean('is_winner')->default(false);
            $table->timestamps();

            $table->index(['auction_id', 'amount']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bids');
    }
};