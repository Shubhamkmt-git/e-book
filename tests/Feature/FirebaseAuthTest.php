<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Services\FirebaseAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class FirebaseAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_firebase_auth_requires_id_token(): void
    {
        $response = $this->postJson(route('customer.firebase-auth'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_token']);
    }

    public function test_firebase_auth_fails_on_invalid_token(): void
    {
        $mockService = Mockery::mock(FirebaseAuthService::class);
        $mockService->shouldReceive('verifyIdToken')
            ->once()
            ->with('invalid-token-123')
            ->andThrow(new \InvalidArgumentException('Malformed Firebase ID token structure.'));

        $this->app->instance(FirebaseAuthService::class, $mockService);

        $response = $this->postJson(route('customer.firebase-auth'), [
            'id_token' => 'invalid-token-123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertFalse(Auth::guard('customer')->check());
    }

    public function test_firebase_auth_authenticates_existing_customer(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'sarah@example.com',
            'name' => 'Sarah Connor',
        ]);

        $mockService = Mockery::mock(FirebaseAuthService::class);
        $mockService->shouldReceive('verifyIdToken')
            ->once()
            ->with('valid-firebase-jwt')
            ->andReturn([
                'uid' => 'firebase_uid_123',
                'email' => 'sarah@example.com',
                'name' => 'Sarah Connor',
                'picture' => 'https://lh3.googleusercontent.com/a/photo.jpg',
                'email_verified' => true,
                'phone_number' => null,
                'claims' => [],
            ]);

        $this->app->instance(FirebaseAuthService::class, $mockService);

        $response = $this->postJson(route('customer.firebase-auth'), [
            'id_token' => 'valid-firebase-jwt',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'email' => 'sarah@example.com',
                ],
            ]);

        $this->assertTrue(Auth::guard('customer')->check());
        $this->assertEquals($customer->id, Auth::guard('customer')->id());
    }

    public function test_firebase_auth_registers_new_customer(): void
    {
        $mockService = Mockery::mock(FirebaseAuthService::class);
        $mockService->shouldReceive('verifyIdToken')
            ->once()
            ->with('new-user-firebase-jwt')
            ->andReturn([
                'uid' => 'firebase_uid_999',
                'email' => 'john.doe@example.com',
                'name' => 'John Doe',
                'picture' => null,
                'email_verified' => true,
                'phone_number' => '+1234567890',
                'claims' => [],
            ]);

        $this->app->instance(FirebaseAuthService::class, $mockService);

        $response = $this->postJson(route('customer.firebase-auth'), [
            'id_token' => 'new-user-firebase-jwt',
            'name' => 'Johnathan Doe',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'john.doe@example.com',
            'name' => 'Johnathan Doe',
        ]);

        $customer = Customer::where('email', 'john.doe@example.com')->first();
        $this->assertNotNull($customer->email_verified_at);
        $this->assertTrue(Auth::guard('customer')->check());
    }

    public function test_firebase_auth_authenticates_phone_number_customer(): void
    {
        $mockService = Mockery::mock(FirebaseAuthService::class);
        $mockService->shouldReceive('verifyIdToken')
            ->once()
            ->with('phone-otp-jwt')
            ->andReturn([
                'uid' => 'firebase_phone_uid_777',
                'email' => null,
                'name' => null,
                'picture' => null,
                'email_verified' => false,
                'phone_number' => '+919876543210',
                'claims' => [],
            ]);

        $this->app->instance(FirebaseAuthService::class, $mockService);

        $response = $this->postJson(route('customer.firebase-auth'), [
            'id_token' => 'phone-otp-jwt',
            'name' => 'Mobile Reader',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'customer' => [
                    'name' => 'Mobile Reader',
                    'mobile' => '+919876543210',
                ],
            ]);

        $this->assertDatabaseHas('customers', [
            'mobile' => '+919876543210',
            'firebase_uid' => 'firebase_phone_uid_777',
            'name' => 'Mobile Reader',
        ]);

        $this->assertTrue(Auth::guard('customer')->check());
    }
}
