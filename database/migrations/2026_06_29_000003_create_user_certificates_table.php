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
        Schema::create('user_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laravel_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('moodle_site_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('moodle_user_id');
            $table->unsignedBigInteger('moodle_customcert_id')->nullable();
            $table->unsignedBigInteger('moodle_customcert_issue_id');
            $table->unsignedBigInteger('moodle_course_module_id')->nullable();
            $table->unsignedBigInteger('moodle_context_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('course_fullname')->nullable();
            $table->string('course_shortname')->nullable();
            $table->string('certificate_name');
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('template_name')->nullable();
            $table->string('certificate_code')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('download_url')->nullable();
            $table->text('verification_url')->nullable();
            $table->boolean('verification_is_public')->default(false);
            $table->string('pdf_stored_path')->nullable();
            $table->json('raw_payload_json')->nullable();
            $table->timestamps();

            $table->unique(['moodle_site_id', 'moodle_customcert_issue_id'], 'user_certs_site_issue_unique');
            $table->index(['laravel_user_id', 'issued_at'], 'user_certs_user_issued_index');
            $table->index(['moodle_site_id', 'moodle_user_id'], 'user_certs_site_user_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_certificates');
    }
};
