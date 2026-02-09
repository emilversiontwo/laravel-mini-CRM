<?php

namespace Tests\Feature\app\Http\Controllers\Api\Ticket;

use App\Enums\Ticket\TicketStatusEnum;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\TicketSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
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
    public function test_store_ticket(): void
    {
        $requestData = [
            'name' => 'John Doe',
            'phone' => '+7 978 501 74 69',
            'email' => 'johndoe@google.com',
            'subject' => 'some subject',
            'text' => 'some text about the ticket',
            'files' => [
                UploadedFile::fake()->image('photo1.jpg'),
                uploadedFile::fake()->image('photo2.jpg'),
                uploadedFile::fake()->image('photo3.jpg'),
            ],
        ];

        $response = $this->postJson(route('tickets.store'), $requestData);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('tickets', ['id' => $response->getOriginalContent()->id]);
    }

    #[Test]
    public function test_index_ticket():void
    {
        $response = $this->actingAs($this->user)->getJson(route('tickets.index'));
        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function test_update_ticket():void
    {
        $requestData = ['status' => TicketStatusEnum::PROCESSED->value];

        $this->actingAs($this->user);

        $response = $this->putJson(route('tickets.update', ['ticket' => $this->ticket]), $requestData);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('tickets', ['id' => $this->ticket->id, 'status' => TicketStatusEnum::PROCESSED->value]);
    }
}
