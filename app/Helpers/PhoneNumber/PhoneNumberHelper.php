<?php

namespace App\Helpers\PhoneNumber;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberHelper
{
    /**
     * @param $phoneNumber
     * @param string $defaultRegion
     * @return string
     * @throws NumberParseException
     */
    public static function formatPhoneNumber($phoneNumber, string $defaultRegion = 'RU'): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $numberProto = $phoneUtil->parse($phoneNumber, $defaultRegion);
        return $phoneUtil->format($numberProto, PhoneNumberFormat::E164);
    }
}
