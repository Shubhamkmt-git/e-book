<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User;
use Tests\TestCase;

class CustomerAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_and_is_authenticated(): void
    {
        $response = $this->post(route('customer.register'), [
            'name' => 'Jane Reader',
            'email' => 'jane.reader@example.com',
            'mobile' => '+91 98765 43210',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated('customer');
        $this->assertDatabaseHas('customers', [
            'name' => 'Jane Reader',
            'email' => 'jane.reader@example.com',
            'mobile' => '+91 98765 43210',
        ]);
    }

    public function test_customer_created_by_admin_can_log_in(): void
    {
        Customer::create([
            'name' => 'Admin Created Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('customer.login'), [
            'email' => 'customer@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated('customer');
    }

    public function test_customer_can_log_out(): void
    {
        $customer = Customer::create([
            'name' => 'Logout Customer',
            'email' => 'logout@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->post(route('customer.logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest('customer');
    }

    public function test_google_redirect_returns_error_when_credentials_not_configured(): void
    {
        config(['services.google.client_id' => null]);
        config(['services.google.client_secret' => null]);

        $response = $this->get(route('auth.google'));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error');
    }

    public function test_google_callback_creates_and_authenticates_customer(): void
    {
        $abstractUser = \Mockery::mock(User::class);
        $abstractUser->shouldReceive('getId')->andReturn('google-123456');
        $abstractUser->shouldReceive('getName')->andReturn('Google User');
        $abstractUser->shouldReceive('getEmail')->andReturn('googleuser@example.com');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/avatar.jpg');

        $provider = \Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('customer.profile'));
        $this->assertAuthenticated('customer');
        $this->assertDatabaseHas('customers', [
            'email' => 'googleuser@example.com',
            'google_id' => 'google-123456',
            'avatar' => 'https://lh3.googleusercontent.com/avatar.jpg',
        ]);
    }
}
