<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::query()->truncate();

        for ($i = 1; $i <= 20; $i++) {
            $customer = Customer::query()->inRandomOrder()->first();

            $ticket = Ticket::factory()->make();

            $ticket->customer()->associate($customer);

            $ticket->save();
        }
        for ($i = 1; $i <= 20; $i++) {
            $customer = Customer::query()->inRandomOrder()->first();

            $ticket = Ticket::factory()->make();
            $ticket->created_at = now()->subDays($i);

            $ticket->customer()->associate($customer);

            $ticket->save();
        }
    }
}
