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
        Schema::create('moodle_user_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laravel_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('moodle_site_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('moodle_user_id');
            $table->string('moodle_idnumber')->nullable();
            $table->string('moodle_username')->nullable();
            $table->string('moodle_email')->nullable();
            $table->enum('linked_via', ['email_code', 'manual_admin', 'api_provisioning']);
            $table->timestamp('linked_at');
            $table->timestamp('last_verified_at')->nullable();
            $table->timestamp('last_certificate_sync_at')->nullable();
            $table->enum('status', ['active', 'pending', 'revoked', 'conflict'])->default('active');
            $table->timestamps();

            $table->unique(['laravel_user_id', 'moodle_site_id']);
            $table->unique(['moodle_site_id', 'moodle_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moodle_user_links');
    }
};
