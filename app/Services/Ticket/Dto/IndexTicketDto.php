<?php

namespace App\Services\Ticket\Dto;

use App\Enums\Ticket\TicketStatusEnum;
use App\Helpers\Dto\Dto;
use App\Helpers\PhoneNumber\PhoneNumberHelper;
use Carbon\Carbon;
use libphonenumber\NumberParseException;

class IndexTicketDto extends Dto
{
    public ?Carbon $from = null {
        set (string|null|Carbon $value) {
            if ($value instanceof Carbon) {
                $this->from = $value;
            } elseif (is_string($value)) {
                $this->from = Carbon::parse($value);
            } else {
                $this->from = $value;
            }
        }
    }

    public ?Carbon $to = null {
        set (string|null|Carbon $value) {
            if ($value instanceof Carbon) {
                $this->to = $value;
            } elseif (is_string($value)) {
                $this->to = Carbon::parse($value);
            } else {
                $this->to = $value;
            }
        }
    }

    public ?string $subject = null;

    public ?string $text = null;

    public ?TicketStatusEnum $status = null {
        set (string|null|TicketStatusEnum $value) {
            if ($value instanceof TicketStatusEnum) {
                $this->status = $value;
            } elseif (is_string($value)) {
                $this->status = TicketStatusEnum::tryFromString($value);
            } else {
                $this->status = $value;
            }
        }
    }

    public ?string $name = null;

    public ?string $email = null;

    public ?string $phone = null {
        /**
         * @throws NumberParseException
         */
        set (null|string $value) {
            if (is_string($value)) {
                $this->phone = PhoneNumberHelper::formatPhoneNumber($value);
            } else {
                $this->phone = $value;
            }
        }
    }
}
