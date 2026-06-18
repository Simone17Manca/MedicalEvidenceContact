<?php

namespace Database\Seeders;

use App\Models\BusinessProfile;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RecoveredLegacyDataSeeder extends Seeder
{
    public function run(): void
    {
        $professional = User::query()->updateOrCreate(
            ['email' => 'giampiero.digregorio@metmi.it'],
            [
                'name' => 'Giampiero DiGregorio',
                'first_name' => 'Giampiero',
                'last_name' => 'DiGregorio',
                'role' => 'professional',
                'phone' => '3476806154',
                'residence' => 'Via dei Tigli 9, Garbagnate Milanese',
                'nationality' => 'Italiana',
                'address_city' => 'Garbagnate Milanese',
                'address_country' => 'Italia',
                'address_province' => 'MI',
                'postal_code' => null,
                'street_address' => 'Via dei Tigli 9',
                'password' => Hash::make('password'),
            ]
        );

        DB::table('professional_profiles')->updateOrInsert(
            ['user_id' => $professional->id],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $business = User::query()->updateOrCreate(
            ['email' => 'simone.manca@metmi.it'],
            [
                'name' => 'Simone Manca',
                'first_name' => 'Simone',
                'last_name' => 'Manca',
                'role' => 'business',
                'phone' => '3662550321',
                'password' => Hash::make('password'),
            ]
        );

        $businessProfile = BusinessProfile::query()->updateOrCreate(
            ['user_id' => $business->id],
            [
                'company_name' => 'metmi',
                'company_type' => 'RSA',
                'location' => 'Milano',
                'employee_count' => 50,
            ]
        );

        $businessProfile->pointsOfContact()->updateOrCreate(
            ['email' => 'simone.manca@metmi.it'],
            [
                'first_name' => 'Simone',
                'last_name' => 'Manca',
                'phone' => '3662550321',
            ]
        );

        $jobPosting = JobPosting::query()->updateOrCreate(
            [
                'user_id' => $business->id,
                'title' => 'Infermiere struttura sanitaria _Campi Bisenzio | Campi Bisenzio (Firenze)',
            ],
            [
                'business_profile_id' => $businessProfile->id,
                'description' => 'La Residenza Anni Azzurri Campi Bisenzio e una struttura moderna con 60 posti letto e un Hospice con 20 posti letto per cure palliative.',
                'positions' => 8,
                'workplace_address' => 'Residenza Anni Azzurri Campi Bisenzio - V. delle Miccine, 1 AC, 50013 Campi Bisenzio (FI), 80 posti letto',
                'required_skills' => "Laurea in Infermieristica o Scienze Infermieristiche; iscrizione all'albo; crediti formativi ECM; gradita esperienza pregressa; automunito; partita IVA se interessato alla libera professione.",
                'contract_type' => 'Part-time',
                'salary_min' => 2000,
                'salary_max' => 2500,
                'expires_at' => '2026-05-27',
                'status' => 'active',
            ]
        );

        JobApplication::query()->updateOrCreate(
            [
                'job_posting_id' => $jobPosting->id,
                'user_id' => $professional->id,
            ],
            ['status' => 'inviata']
        );
    }
}