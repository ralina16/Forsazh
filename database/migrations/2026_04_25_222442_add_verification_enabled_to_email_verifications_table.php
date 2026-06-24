<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EmailVerification;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('email_verifications', 'verification_enabled')) {
            Schema::table('email_verifications', function (Blueprint $table) {
                $table->boolean('verification_enabled')->default(true)->after('expires_at');
            });
        }

        if (!EmailVerification::where('email', '__system_settings__')->exists()) {
            EmailVerification::create([
                'email' => '__system_settings__',
                'code' => '000000',
                'expires_at' => now()->addYear(), 
                'verification_enabled' => true,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('email_verifications', 'verification_enabled')) {
            Schema::table('email_verifications', function (Blueprint $table) {
                $table->dropColumn('verification_enabled');
            });
        }
    }
};