<?php

namespace App\Enums\Ticket;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: "TicketStatusEnum", description: "Ticket status", type: "string", enum: ["new", "at_work", "processed"], example: "new")]
enum TicketStatusEnum: string
{
    case NEW = 'new';
    case AT_WORK = 'at_work';
    case PROCESSED = 'processed';

    public static function tryFromString(string $value): self
    {
        return match ($value) {
            self::NEW->value => self::NEW,
            self::AT_WORK->value => self::AT_WORK,
            self::PROCESSED->value => self::PROCESSED,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'новый',
            self::AT_WORK => 'в работе',
            self::PROCESSED => 'обработан',
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
