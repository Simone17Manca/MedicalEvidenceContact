<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('professional_professions')) {
            Schema::create('professional_professions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('profession', 50)->default('oss');
                $table->timestamps();

                $table->unique('user_id');
            });
        }

        $now = now();

        DB::table('users')
            ->where('role', 'professional')
            ->orderBy('id')
            ->select('id')
            ->chunk(100, function ($users) use ($now) {
                foreach ($users as $user) {
                    DB::table('professional_professions')->updateOrInsert(
                        ['user_id' => $user->id],
                        [
                            'profession' => 'oss',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_professions');
    }
};