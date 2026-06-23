<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('chat_type')->default('mini');
            $table->text('message_text');
            $table->enum('message_type', ['sent', 'received'])->default('sent');
            $table->string('message_format', 20)->default('text');
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->nullable();
            
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};