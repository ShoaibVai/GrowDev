<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user = User::factory()->create([
            'totp_secret' => $secret,
        ]);

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
            'totp_code' => $google2fa->getCurrentOtp($secret),
        ]);

        $response
            ->assertSessionHas('status')
            ->assertRedirect(route('password.reset.form'));
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user = User::factory()->create([
            'totp_secret' => $secret,
        ]);

        $this->post('/forgot-password', [
            'email' => $user->email,
            'totp_code' => $google2fa->getCurrentOtp($secret),
        ]);

        $response = $this->get('/reset-password');

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_after_totp_verification(): void
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user = User::factory()->create([
            'totp_secret' => $secret,
        ]);

        $this->post('/forgot-password', [
            'email' => $user->email,
            'totp_code' => $google2fa->getCurrentOtp($secret),
        ]);

        $response = $this->post('/reset-password', [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response
            ->assertSessionHas('status')
            ->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
