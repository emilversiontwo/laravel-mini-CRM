<?php

namespace Tests\Feature\app\Http\Controllers\Api\Auth;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public User $user2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);

        $this->user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->first();

        $this->user2 = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->first();
    }

    #[Test]
    public function test_login(): void
    {
        $requestData = [
            'email' => $this->user->email,
            'password' => 'password',
        ];

        $response = $this->postJson(route('api.login'), $requestData);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    #[Test]
    public function test_login_fail(): void
    {
        $requestData = [
            'email' => $this->user->email,
            'password' => 'WrongPassword',
        ];

        $response = $this->postJson(route('api.login'), $requestData);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    #[Test]
    public function test_logout(): void
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->postJson(route('api.logout'))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertEquals(null, $this->user->currentAccessToken());
    }
}
