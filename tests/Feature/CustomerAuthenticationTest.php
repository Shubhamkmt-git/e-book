<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
}
