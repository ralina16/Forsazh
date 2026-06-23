<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('car_config_id')
                  ->nullable()
                  ->constrained('car_configs')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['car_config_id']);
            $table->dropColumn('car_config_id');
        });
    }
};