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
        $isbn = preg_replace('/[^0-9]/', '', $value);

        if (strlen($isbn) !== 13) {
            $fail('The ' . $attribute . ' has less 13 numbers.');
        } else {
            $sum = 0;
            for ($i = 0; $i < 12; $i++) {
                $digit = (int) $isbn[$i];
                $par = $i % 2 === 0;
                $sum += $par ? $digit * 1 : $digit * 3;
            }

            $digitControl = (10 - ($sum % 10)) % 10;

            if ($digitControl !== (int) $isbn[12]) {
                $fail('The ' . $attribute . ' is false.');
            }
        }
    }
}
