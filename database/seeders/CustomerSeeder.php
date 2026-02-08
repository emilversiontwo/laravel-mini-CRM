<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::query()->truncate();

        for ($i = 1; $i <= 10; $i++) {
            Customer::factory()->create();
        }
    }
}
