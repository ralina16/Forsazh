<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique(); 
            $table->string('name', 100); 
            $table->string('phone', 20); 
            $table->timestamp('created_at')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->unsignedInteger('message_count')->default(0); 
                 $table->boolean('is_guest')->default(true);
            
            $table->index('user_id');
            $table->index('last_activity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_users');
    }
};