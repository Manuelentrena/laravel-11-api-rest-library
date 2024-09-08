<?php

namespace App\Rules\API\V1;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Isbn13 implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += ($i % 2 === 0) ? $value[$i] * 1 : $value[$i] * 3;
        }

        $digitControl = (10 - ($sum % 10)) % 10;

        if ($digitControl !== $value[12]) {
            $fail('The ' . $attribute . ' is false.');
        }
    }
}
