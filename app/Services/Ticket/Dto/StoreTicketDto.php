<?php

namespace App\Services\Ticket\Dto;

use App\Helpers\Dto\Dto;
use App\Models\Customer;

class StoreTicketDto extends Dto
{
    public string $subject;

    public string $text;

    public ?array $files = null;

    public Customer $customer;
}
