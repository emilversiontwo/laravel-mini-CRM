<?php

namespace Database\Factories;

use App\Enums\Ticket\TicketStatusEnum;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @mixin Factory<Ticket> */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $manager_responded = $this->faker->boolean();

        return [
            'subject' => $this->faker->word(),
            'text' => $this->faker->text(),
            'status' => $manager_responded
                ? $this->faker->randomElement([
                    TicketStatusEnum::AT_WORK->getValue(),
                    TicketStatusEnum::PROCESSED->getValue()
                ])
                : TicketStatusEnum::NEW->getValue(),
            'manager_responded' => $manager_responded ? Carbon::now() : null,
        ];
    }
}
