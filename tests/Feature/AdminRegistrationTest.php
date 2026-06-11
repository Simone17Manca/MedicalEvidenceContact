<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/register');

        $response->assertStatus(200);
    }

    public function test_admin_users_can_register_from_admin_registration(): void
    {
        $response = $this->post('/admin/register', [
            'first_name' => 'Ada',
            'last_name' => 'Admin',
            'email' => 'ada.admin@example.com',
            'phone' => '333111222',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'ada.admin@example.com',
            'role' => 'admin',
            'first_name' => 'Ada',
            'last_name' => 'Admin',
        ]);
        $this->assertDatabaseCount('professional_profiles', 0);
        $this->assertDatabaseCount('business_profiles', 0);
    }
}
