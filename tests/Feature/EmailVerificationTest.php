<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\EmailVerificationCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_verification_code(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->firstOrFail();

        $response->assertRedirect(route('verification.notice'));
        $this->assertGuest();
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertNotNull($user->email_verification_code);
        Notification::assertSentTo($user, EmailVerificationCode::class);
    }

    public function test_unverified_login_sends_code_and_redirects_to_verification_notice(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create([
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertGuest();
        Notification::assertSentTo($user, EmailVerificationCode::class);
    }

    public function test_unverified_users_are_redirected_from_protected_pages(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verification_code_marks_email_as_verified(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $code = null;

        Notification::assertSentTo(
            $user,
            EmailVerificationCode::class,
            function (EmailVerificationCode $notification) use (&$code) {
                $code = $notification->code();

                return true;
            },
        );

        $response = $this->post(route('verification.verify'), [
            'code' => $code,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
