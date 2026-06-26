<?php

namespace Tests\Feature;

use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_dashboard_users_and_job_postings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $business = User::factory()->create(['role' => 'business']);

        JobPosting::create([
            'user_id' => $business->id,
            'title' => 'Annuncio visibile admin',
            'description' => 'Admin vede tutti gli annunci.',
            'positions' => 1,
            'workplace_address' => 'Via Roma 1',
            'contract_type' => 'Tempo determinato',
            'expires_at' => now()->addWeek()->toDateString(),
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard admin');

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee($business->email);

        $this->actingAs($admin)
            ->get(route('admin.job-postings.index'))
            ->assertOk()
            ->assertSee('Annuncio visibile admin');
    }

    public function test_non_admin_cannot_access_admin_management(): void
    {
        $professional = User::factory()->create(['role' => 'professional']);

        $this->actingAs($professional)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($professional)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs($professional)
            ->get(route('admin.job-postings.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'role' => 'professional',
                'first_name' => 'Laura',
                'last_name' => 'Verdi',
                'email' => 'laura.verdi@example.test',
                'phone' => '333222111',
                'password' => 'password',
                'password_confirmation' => 'password',
                'nationality' => 'Italiana',
                'address_city' => 'Roma',
                'address_country' => 'Italia',
                'address_province' => 'RM',
                'postal_code' => '00100',
                'street_address' => 'Via Roma 10',
            ]);

        $response->assertSessionHasNoErrors();
        $user = User::where('email', 'laura.verdi@example.test')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user), [
                'role' => 'professional',
                'first_name' => 'Laura',
                'last_name' => 'Bianchi',
                'email' => 'laura.bianchi@example.test',
                'phone' => '333222111',
                'nationality' => 'Italiana',
                'address_city' => 'Milano',
                'address_country' => 'Italia',
                'address_province' => 'MI',
                'postal_code' => '20100',
                'street_address' => 'Via Milano 20',
            ])
            ->assertRedirect(route('admin.users.edit', $user, absolute: false));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'laura.bianchi@example.test',
            'last_name' => 'Bianchi',
            'address_city' => 'Milano',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users.index', absolute: false));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_can_create_business_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'role' => 'business',
                'first_name' => 'Marco',
                'last_name' => 'Business',
                'email' => 'marco.business@example.test',
                'phone' => '333000111',
                'password' => 'password',
                'password_confirmation' => 'password',
                'company_name' => 'Clinica Business',
                'company_type' => 'Clinica privata',
                'location' => 'Torino',
                'employee_count' => 40,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'email' => 'marco.business@example.test',
            'role' => 'business',
        ]);
        $this->assertDatabaseHas('business_profiles', [
            'company_name' => 'Clinica Business',
            'location' => 'Torino',
        ]);
    }

    public function test_admin_can_create_admin_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'role' => 'admin',
                'first_name' => 'Ada',
                'last_name' => 'Staff',
                'email' => 'ada.staff@example.test',
                'phone' => '333444555',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'email' => 'ada.staff@example.test',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_create_update_and_delete_job_posting(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $business = User::factory()->create(['role' => 'business']);

        $businessProfile = $business->businessProfile()->create([
            'company_name' => 'Clinica Admin',
            'company_type' => 'Clinica privata',
            'location' => 'Roma',
            'employee_count' => 30,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.job-postings.store'), [
                'user_id' => $business->id,
                'title' => 'Annuncio admin',
                'description' => 'Creato da admin.',
                'positions' => 2,
                'workplace_address' => 'Via Napoli 1',
                'required_skills' => 'Iscrizione albo',
                'contract_type' => 'Tempo determinato',
                'salary_min' => 20000,
                'salary_max' => 26000,
                'expires_at' => now()->addMonth()->toDateString(),
                'status' => 'active',
            ]);

        $response->assertSessionHasNoErrors();
        $jobPosting = JobPosting::where('title', 'Annuncio admin')->firstOrFail();

        $this->assertSame($businessProfile->id, $jobPosting->business_profile_id);

        $this->actingAs($admin)
            ->put(route('admin.job-postings.update', $jobPosting), [
                'user_id' => $business->id,
                'title' => 'Annuncio admin aggiornato',
                'description' => 'Aggiornato da admin.',
                'positions' => 3,
                'workplace_address' => 'Via Torino 2',
                'required_skills' => 'Esperienza reparto',
                'contract_type' => 'Tempo indeterminato',
                'salary_min' => 28000,
                'salary_max' => 34000,
                'expires_at' => now()->addMonth()->toDateString(),
                'status' => 'active',
            ])
            ->assertRedirect(route('admin.job-postings.edit', $jobPosting, absolute: false));

        $this->assertDatabaseHas('job_postings', [
            'id' => $jobPosting->id,
            'title' => 'Annuncio admin aggiornato',
            'positions' => 3,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.job-postings.destroy', $jobPosting))
            ->assertRedirect(route('admin.job-postings.index', absolute: false));

        $this->assertDatabaseMissing('job_postings', ['id' => $jobPosting->id]);
    }
}
