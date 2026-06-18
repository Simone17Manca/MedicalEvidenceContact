<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('name="profession"', false);
        $response->assertSee('<select id="profession"', false);
        $response->assertSee('Operatore Socio Sanitario');
        $response->assertSee('Infermiere');
        $response->assertSee('Anestesista');
        $response->assertSee('Fisioterapista');
        $response->assertSee('name="nationality"', false);
        $response->assertSee('<select id="nationality"', false);
        $response->assertDontSee('<input id="nationality"', false);
    }

    public function test_registration_screen_cannot_be_rendered_if_support_is_disabled(): void
    {
        if (Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_professional_users_can_register(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'account_type' => 'professional',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '3331234567',
            'nationality' => 'Italiana',
            'profession' => 'infermiere',
            'address_city' => 'Roma',
            'address_country' => 'Italia',
            'address_province' => 'RM',
            'postal_code' => '00100',
            'street_address' => 'Via Nazionale 10',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'professional',
            'first_name' => 'Test',
            'last_name' => 'User',
            'nationality' => 'Italiana',
            'address_city' => 'Roma',
            'address_country' => 'Italia',
            'address_province' => 'RM',
            'postal_code' => '00100',
            'street_address' => 'Via Nazionale 10',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseCount('professional_profiles', 1);
        $this->assertDatabaseHas('professional_professions', [
            'profession' => 'infermiere',
        ]);
    }

    public function test_professional_registration_rejects_manual_nationality_values(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'account_type' => 'professional',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'manual-nationality@example.com',
            'phone' => '3331234567',
            'nationality' => 'Valore scritto a mano',
            'profession' => 'oss',
            'address_city' => 'Roma',
            'address_country' => 'Italia',
            'address_province' => 'RM',
            'postal_code' => '00100',
            'street_address' => 'Via Nazionale 10',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $response->assertSessionHasErrors('nationality');
        $this->assertDatabaseMissing('users', [
            'email' => 'manual-nationality@example.com',
        ]);
    }

    public function test_professional_registration_rejects_manual_profession_values(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'account_type' => 'professional',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'manual-profession@example.com',
            'phone' => '3331234567',
            'nationality' => 'Italiana',
            'profession' => 'chirurgo',
            'address_city' => 'Roma',
            'address_country' => 'Italia',
            'address_province' => 'RM',
            'postal_code' => '00100',
            'street_address' => 'Via Nazionale 10',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $response->assertSessionHasErrors('profession');
        $this->assertDatabaseMissing('users', [
            'email' => 'manual-profession@example.com',
        ]);
    }

    public function test_business_users_can_register(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'account_type' => 'business',
            'first_name' => 'Business',
            'last_name' => 'Owner',
            'email' => 'business@example.com',
            'phone' => '021234567',
            'company_name' => 'Clinica San Carlo',
            'company_type' => 'Clinica privata',
            'location' => 'Milano',
            'employee_count' => 120,
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('users', [
            'email' => 'business@example.com',
            'role' => 'business',
        ]);
        $this->assertDatabaseHas('business_profiles', [
            'company_name' => 'Clinica San Carlo',
            'company_type' => 'Clinica privata',
            'location' => 'Milano',
        ]);
        $this->assertDatabaseCount('business_points_of_contact', 0);
    }

    public function test_admin_users_cannot_register_from_public_registration(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $this->post('/register', [
            'account_type' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone' => '3330000000',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', [
            'email' => 'admin@example.com',
        ]);
    }
}
