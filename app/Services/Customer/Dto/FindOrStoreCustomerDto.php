<?php

namespace App\Services\Customer\Dto;

use App\Helpers\Dto\Dto;
use App\Helpers\PhoneNumber\PhoneNumberHelper;
use libphonenumber\NumberParseException;

class FindOrStoreCustomerDto extends Dto
{
    public string $name;

    public string $email;

    public string $phone {
        /**
         * @throws NumberParseException
         */
        set (string $phone) {
            $this->phone = PhoneNumberHelper::formatPhoneNumber($phone);
        }
    }
}
