<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class ValidatePhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($value, 'RU');

            if (!$phoneUtil->isValidNumber($numberProto)) {
                $fail('The phone number is not valid.');
            }
        } catch (NumberParseException $e) {
            $fail($e->getMessage());
        }
    }
}
