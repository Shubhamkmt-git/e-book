<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_orders(): void
    {
        $response = $this->get(route('admin.orders.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_orders_index(): void
    {
        $admin = User::factory()->create();
        $customer = Customer::factory()->create([
            'name' => 'Alice Customer',
            'email' => 'alice@example.com',
        ]);

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'test-book-slug',
            'book_title' => 'Mastering Python & AI',
            'amount' => 499.00,
            'transaction_id' => 'EB1234567890TEST',
            'status' => 'paid',
            'gateway_response' => ['status' => 'success', 'txnid' => 'EB1234567890TEST'],
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));

        $response->assertOk();
        $response->assertSee('Orders & Transactions', false);
        $response->assertSee('EB1234567890TEST');
        $response->assertSee('Alice Customer');
        $response->assertSee('Mastering Python');
        $response->assertSee('Paid');
    }

    public function test_admin_can_filter_orders_by_status(): void
    {
        $admin = User::factory()->create();
        $customer = Customer::factory()->create();

        Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'book-1',
            'book_title' => 'Paid Book Title',
            'amount' => 299.00,
            'transaction_id' => 'TXNPAID123',
            'status' => 'paid',
        ]);

        Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'book-2',
            'book_title' => 'Pending Book Title',
            'amount' => 399.00,
            'transaction_id' => 'TXNPENDING123',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index', ['status' => 'paid']));

        $response->assertOk();
        $response->assertSee('TXNPAID123');
        $response->assertDontSee('TXNPENDING123');
    }

    public function test_admin_can_view_order_details(): void
    {
        $admin = User::factory()->create();
        $customer = Customer::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'unique-guide',
            'book_title' => 'The Complete Flutter Guide',
            'amount' => 599.00,
            'transaction_id' => 'EBFLUTTER999',
            'status' => 'paid',
            'gateway_response' => ['mode' => 'UPI', 'status' => 'success'],
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $purchase));

        $response->assertOk();
        $response->assertSee('Order #'.$purchase->id);
        $response->assertSee('EBFLUTTER999');
        $response->assertSee('The Complete Flutter Guide');
        $response->assertSee('John Doe');
        $response->assertSee('john@example.com');
    }

    public function test_admin_can_update_order_status(): void
    {
        $admin = User::factory()->create();
        $customer = Customer::factory()->create();

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'guide-1',
            'book_title' => 'Rust for Beginners',
            'amount' => 199.00,
            'transaction_id' => 'EBRUST123',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $purchase), [
            'status' => 'paid',
        ]);

        $response->assertRedirect();
        $this->assertEquals('paid', $purchase->fresh()->status);
    }

    public function test_admin_can_delete_order(): void
    {
        $admin = User::factory()->create();
        $customer = Customer::factory()->create();

        $purchase = Purchase::create([
            'customer_id' => $customer->id,
            'book_identifier' => 'delete-book',
            'book_title' => 'Delete Me',
            'amount' => 99.00,
            'transaction_id' => 'EBDELETE123',
            'status' => 'failed',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.orders.destroy', $purchase));

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseMissing('purchases', ['id' => $purchase->id]);
    }
}
