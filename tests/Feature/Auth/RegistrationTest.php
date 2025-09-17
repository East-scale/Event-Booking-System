<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    // pre made
    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }
    // premade
    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    // Tests the registration requires a privacy policy
    public function test_registration_requires_privacy_policy(): void
    {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        // privacy_policy not included
    ]);

    $response->assertSessionHasErrors(['privacy_policy']);
    $this->assertGuest();
    }

    // test the registration works with privacy policy
    public function test_registration_works_with_privacy_policy(): void
    {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'privacy_policy' => '1',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/dashboard');
    }


}
