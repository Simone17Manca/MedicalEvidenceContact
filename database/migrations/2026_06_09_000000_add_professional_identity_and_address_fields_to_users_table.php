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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nationality')->nullable()->after('residence');
            $table->string('address_city')->nullable()->after('nationality');
            $table->string('address_country')->nullable()->after('address_city');
            $table->string('address_province', 100)->nullable()->after('address_country');
            $table->string('postal_code', 20)->nullable()->after('address_province');
            $table->string('street_address')->nullable()->after('postal_code');
            $table->string('residence_permit_path')->nullable()->after('street_address');
            $table->string('ata_certificate_path')->nullable()->after('residence_permit_path');
        });

        DB::table('users')
            ->where('role', 'professional')
            ->whereNull('nationality')
            ->update([
                'nationality' => 'Italiana',
                'address_country' => 'Italia',
                'address_city' => DB::raw('residence'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nationality',
                'address_city',
                'address_country',
                'address_province',
                'postal_code',
                'street_address',
                'residence_permit_path',
                'ata_certificate_path',
            ]);
        });
    }
};
