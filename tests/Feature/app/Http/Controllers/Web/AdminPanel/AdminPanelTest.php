<?php

namespace Tests\Feature\app\Http\Controllers\Web\AdminPanel;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Tests\TestCase;

class AdminPanelTest extends TestCase
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
    public function test_get_dashboard()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertViewIs('AdminPanel.dashboard');
    }

    #[Test]
    public function test_get_dashboard_fail()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }
}
