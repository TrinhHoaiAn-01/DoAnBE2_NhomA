<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthRecaptchaTest extends TestCase
{
    public function test_login_requires_recaptcha(): void
    {
        Http::fake();

        $response = $this->from(route('login'))->post(route('login.submit'), [
            'email' => 'customer@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('g-recaptcha-response');
        Http::assertNothingSent();
    }

    public function test_register_rejects_failed_recaptcha_verification(): void
    {
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
            ]),
        ]);

        $response = $this->from(route('register'))->post(route('register.submit'), [
            'username' => 'captcha-test-user',
            'email' => 'captcha-test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'g-recaptcha-response' => 'invalid-token',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('g-recaptcha-response');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://www.google.com/recaptcha/api/siteverify'
                && $request['response'] === 'invalid-token';
        });
    }

    public function test_forgot_password_requires_recaptcha(): void
    {
        Http::fake();

        $response = $this->from(route('password.request'))->post(route('password.update.fake'), [
            'email' => 'customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHasErrors('g-recaptcha-response');
        Http::assertNothingSent();
    }
}
