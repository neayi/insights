<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Checks a Cloudflare Turnstile token against the siteverify API.
 * Disabled (always passes) when no secret key is configured, e.g. in dev or tests.
 */
class Turnstile implements ValidationRule
{
    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function __construct(private string $action, private ?string $ip = null, private ?string $hostname = null)
    {
    }

    public static function enabled(): bool
    {
        return !empty(config('neayi.turnstile.secret_key')) && !empty(config('neayi.turnstile.site_key'));
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!self::enabled()) {
            return;
        }

        if (!is_string($value) || $value === '') {
            $fail(__('auth.captcha_failed'));
            return;
        }

        try {
            $response = Http::asForm()->timeout(5)->post(self::VERIFY_URL, [
                'secret' => config('neayi.turnstile.secret_key'),
                'response' => $value,
                'remoteip' => $this->ip,
            ]);
        } catch (\Throwable $e) {
            // Don't block legitimate users if Cloudflare is unreachable, the other checks still apply
            Log::warning('Turnstile verification unavailable: '.$e->getMessage());
            return;
        }

        // A token is only valid for the widget action and the site it was issued for
        if ($response->json('success') !== true
            || $response->json('action') !== $this->action
            || ($this->hostname !== null && $response->json('hostname') !== $this->hostname)) {
            Log::info('Turnstile verification failed', [
                'errors' => $response->json('error-codes'),
                'action' => $response->json('action'),
                'hostname' => $response->json('hostname'),
                'ip' => $this->ip,
            ]);
            $fail(__('auth.captcha_failed'));
        }
    }
}
