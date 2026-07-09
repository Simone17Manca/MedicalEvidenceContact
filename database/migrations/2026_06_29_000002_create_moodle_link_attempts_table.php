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
        Schema::create('moodle_link_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laravel_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('moodle_site_id')->constrained()->cascadeOnDelete();
            $table->enum('lookup_type', ['email', 'username']);
            $table->string('lookup_value_hash', 128);
            $table->string('lookup_value_masked')->nullable();
            $table->unsignedBigInteger('moodle_user_id')->nullable();
            $table->string('moodle_email_masked')->nullable();
            $table->string('verification_code_hash')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('consumed_at')->nullable();
            $table->unsignedTinyInteger('attempts_count')->default(0);
            $table->enum('status', [
                'created',
                'sent',
                'verified',
                'expired',
                'failed',
                'cancelled',
            ])->default('created');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['laravel_user_id', 'moodle_site_id', 'status']);
            $table->index(['moodle_site_id', 'moodle_user_id']);
            $table->index('lookup_value_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moodle_link_attempts');
    }
};
