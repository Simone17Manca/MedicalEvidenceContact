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
        Schema::create('moodle_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('base_url')->unique();
            $table->text('api_token_encrypted');
            $table->enum('certificate_sync_driver', [
                'native_mod_customcert',
                'local_laravelcertsync',
                'disabled',
            ])->default('disabled');
            $table->boolean('enabled')->default(false);
            $table->timestamp('last_user_sync_at')->nullable();
            $table->timestamp('last_certificate_sync_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moodle_sites');
    }
};
