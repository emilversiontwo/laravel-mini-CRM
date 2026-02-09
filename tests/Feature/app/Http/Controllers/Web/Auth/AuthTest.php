<?php

namespace Tests\Feature\app\Http\Controllers\Web\Auth;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Termwind\Components\Dd;
use Tests\TestCase;

class AuthTest extends TestCase
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
        $response = $this->get(route('login'));
        $response->assertOk();
    }

    #[Test]
    public function test_authenticate(): void
    {
        $token = Str::random(40);
        $this->withSession(['_token' => $token]);

        $requestData = [
            'email' => $this->user->email,
            'password' => 'password',
            '_token'   => $token,
        ];

        $response = $this->post(route('authenticate'), $requestData);

        $this->assertAuthenticated();

        $response->assertRedirectToRoute('admin.dashboard');
    }

    #[Test]
    public function test_logout(): void
    {
        $this->actingAs($this->user);

        $token = Str::random(40);
        $this->withSession(['_token' => $token]);

        $requestData = [
            '_token'   => $token,
        ];

        $response = $this->post(route('logout'), $requestData);

        $this->assertGuest();

        $response->assertRedirect('/');
    }
}
