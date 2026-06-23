<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('insurance_requests', function (Blueprint $table) {
        $table->id();
        $table->string('fio');
        $table->string('phone');
        $table->enum('insurance_type', ['osago', 'kasko']);
        $table->decimal('car_price', 12, 2);
        $table->string('car_age');
        $table->decimal('estimated_premium', 12, 2);
        $table->decimal('monthly_payment', 10, 2);
        $table->enum('risk_level', ['низкий', 'средний', 'высокий']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_requests');
    }
};
