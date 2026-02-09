<?php

namespace Tests\Feature\app\Http\Controllers\Web\AdminPanel;

use App\Enums\Ticket\TicketStatusEnum;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\TicketSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Ticket $ticket;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CustomerSeeder::class,
            TicketSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);

        $this->user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->first();

        $this->ticket = Ticket::query()->where('status', '=', TicketStatusEnum::NEW->getValue())->first();
    }

    #[Test]
    public function test_index()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.tickets.index'));

        $response->assertOk();
        $response->assertViewIs('AdminPanel.Ticket.index');
    }

    #[Test]
    public function test_edit()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.tickets.edit', ['ticket' => $this->ticket->id]));

        $response->assertOk();
        $response->assertViewIs('AdminPanel.Ticket.edit');
    }
}
