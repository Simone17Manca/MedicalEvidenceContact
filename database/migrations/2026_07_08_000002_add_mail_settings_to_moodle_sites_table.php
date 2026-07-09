<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('moodle_sites', function (Blueprint $table) {
            $table->string('mail_from_address')->nullable()->after('api_token_encrypted');
            $table->string('mail_from_name')->nullable()->after('mail_from_address');
            $table->string('mail_mailer')->default('smtp')->after('mail_from_name');
            $table->string('mail_host')->nullable()->after('mail_mailer');
            $table->unsignedInteger('mail_port')->nullable()->after('mail_host');
            $table->string('mail_username')->nullable()->after('mail_port');
            $table->text('mail_password_encrypted')->nullable()->after('mail_username');
            $table->string('mail_encryption')->nullable()->after('mail_password_encrypted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moodle_sites', function (Blueprint $table) {
            $table->dropColumn([
                'mail_from_address',
                'mail_from_name',
                'mail_mailer',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password_encrypted',
                'mail_encryption',
            ]);
        });
    }
};