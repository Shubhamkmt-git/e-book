<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCustomerCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
    }

    public function test_admin_can_view_customers_list(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'testcustomer@example.com',
            'mobile' => '9876543210',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->get(route('admin.customers.index'));

        $response->assertOk();
        $response->assertSee('Test Customer');
        $response->assertSee('testcustomer@example.com');
    }

    public function test_admin_can_create_customer_without_password(): void
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.customers.store'), [
                'name' => 'John Passwordless',
                'email' => 'johnpass@example.com',
                'mobile' => '9988776655',
            ]);

        $response->assertRedirect(route('admin.customers.index'));

        $this->assertDatabaseHas('customers', [
            'name' => 'John Passwordless',
            'email' => 'johnpass@example.com',
            'mobile' => '9988776655',
        ]);
    }

    public function test_admin_can_update_customer_without_password(): void
    {
        $customer = Customer::create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'mobile' => '1122334455',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->put(route('admin.customers.update', $customer), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'mobile' => '9988112233',
            ]);

        $response->assertRedirect(route('admin.customers.index'));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'mobile' => '9988112233',
        ]);
    }

    public function test_admin_can_delete_customer(): void
    {
        $customer = Customer::create([
            'name' => 'Delete Me',
            'email' => 'deleteme@example.com',
        ]);

        $response = $this->actingAs($this->admin, 'web')
            ->delete(route('admin.customers.destroy', $customer));

        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }
}
