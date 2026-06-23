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
    Schema::create('credit_requests', function (Blueprint $table) {
        $table->id();
        $table->string('fio');
        $table->string('phone');
        $table->enum('car_type', ['new', 'used']);
        $table->decimal('credit_amount', 12, 2);
        $table->decimal('interest_rate', 5, 2);
        $table->integer('loan_term');
        $table->decimal('monthly_payment', 10, 2);
        $table->boolean('insurance_kasko')->default(false);
        $table->boolean('insurance_as_z')->default(false);
        $table->boolean('early_repayment')->default(false);
        $table->text('notes')->nullable();
        $table->boolean('consent')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_requests');
    }
};
