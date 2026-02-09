<?php

namespace App\Services\Ticket\Dto;

use App\Enums\Ticket\TicketStatusEnum;
use App\Helpers\Dto\Dto;
use App\Models\Ticket;

class UpdateTicketDto extends Dto
{
    public Ticket $ticket;

    public TicketStatusEnum $status {
        set (string|TicketStatusEnum $value) {
            if ($value instanceof TicketStatusEnum) {
                $this->status = $value;
            } else {
                $this->status = TicketStatusEnum::tryFromString($value);
            }
        }
    }
}
