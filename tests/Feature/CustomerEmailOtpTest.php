<?php

namespace Tests\Feature;

use App\Mail\CustomerOtpMail;
use App\Models\Customer;
use App\Models\CustomerOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CustomerEmailOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_request_registration_otp(): void
    {
        Mail::fake();

        $response = $this->postJson(route('customer.send-otp'), [
            'name' => 'Alice Reader',
            'email' => 'alice@example.com',
            'mobile' => '9876543210',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'email' => 'alice@example.com',
            ]);

        $this->assertDatabaseHas('customer_otps', [
            'email' => 'alice@example.com',
            'name' => 'Alice Reader',
        ]);

        Mail::assertSent(CustomerOtpMail::class, function ($mail) {
            return $mail->hasTo('alice@example.com') && strlen($mail->otpCode) === 6;
        });
    }

    public function test_registration_otp_fails_if_email_is_already_registered(): void
    {
        Customer::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson(route('customer.send-otp'), [
            'name' => 'Duplicate User',
            'email' => 'existing@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_customer_can_verify_otp_and_complete_registration(): void
    {
        $otp = CustomerOtp::generateFor(
            email: 'bob@example.com',
            name: 'Bob Bookworm',
            mobile: '9123456780'
        );

        $response = $this->postJson(route('customer.verify-otp'), [
            'email' => 'bob@example.com',
            'otp' => $otp->otp_code,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'bob@example.com',
            'name' => 'Bob Bookworm',
        ]);

        $customer = Customer::where('email', 'bob@example.com')->first();
        $this->assertNotNull($customer->email_verified_at);
        $this->assertTrue(Auth::guard('customer')->check());
        $this->assertEquals($customer->id, Auth::guard('customer')->id());

        // OTP should be deleted after successful verification
        $this->assertDatabaseMissing('customer_otps', [
            'email' => 'bob@example.com',
        ]);
    }

    public function test_invalid_otp_is_rejected(): void
    {
        CustomerOtp::generateFor(
            email: 'charlie@example.com',
            name: 'Charlie'
        );

        $response = $this->postJson(route('customer.verify-otp'), [
            'email' => 'charlie@example.com',
            'otp' => '000000',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertDatabaseMissing('customers', [
            'email' => 'charlie@example.com',
        ]);
    }

    public function test_resend_otp_rate_limiting(): void
    {
        Mail::fake();

        $otp = CustomerOtp::generateFor(
            email: 'david@example.com',
            name: 'David'
        );

        // Immediate resend should trigger 429
        $response = $this->postJson(route('customer.resend-otp'), [
            'email' => 'david@example.com',
        ]);

        $response->assertStatus(429);
    }

    public function test_customer_can_request_login_otp(): void
    {
        Mail::fake();

        $customer = Customer::factory()->create([
            'email' => 'emma@example.com',
            'name' => 'Emma Watson',
        ]);

        $response = $this->postJson(route('customer.send-login-otp'), [
            'email' => 'emma@example.com',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'email' => 'emma@example.com',
                'is_existing' => true,
            ]);

        Mail::assertSent(CustomerOtpMail::class, function ($mail) {
            return $mail->hasTo('emma@example.com') && strlen($mail->otpCode) === 6;
        });
    }

    public function test_customer_can_verify_login_otp_and_sign_in(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'frank@example.com',
            'name' => 'Frank Miller',
        ]);

        $otp = CustomerOtp::generateFor(
            email: 'frank@example.com',
            name: 'Frank Miller'
        );

        $response = $this->postJson(route('customer.verify-login-otp'), [
            'email' => 'frank@example.com',
            'otp' => $otp->otp_code,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertTrue(Auth::guard('customer')->check());
        $this->assertEquals($customer->id, Auth::guard('customer')->id());
    }

    public function test_new_customer_can_login_with_otp_and_auto_creates_account(): void
    {
        $otp = CustomerOtp::generateFor(
            email: 'grace@example.com',
            name: 'Grace Hopper'
        );

        $response = $this->postJson(route('customer.verify-login-otp'), [
            'email' => 'grace@example.com',
            'otp' => $otp->otp_code,
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'grace@example.com',
        ]);

        $this->assertTrue(Auth::guard('customer')->check());
    }
}
