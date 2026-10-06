<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rejects forms submitted too quickly after being displayed (bots post instantly).
 * The value is the encrypted timestamp produced by MinimumFillTime::token() when rendering the form.
 */
class MinimumFillTime implements ValidationRule
{
    public function __construct(private int $seconds = 3)
    {
    }

    public static function token(): string
    {
        return encrypt(time());
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $renderedAt = (int) decrypt((string) $value);
        } catch (DecryptException $e) {
            $fail(__('auth.form_rejected'));
            return;
        }

        if (time() - $renderedAt < $this->seconds) {
            $fail(__('auth.form_rejected'));
        }
    }
}
