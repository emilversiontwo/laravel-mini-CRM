<?php

namespace Tests\Feature\app\Http\Controllers\Web\AdminPanel;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public User $user2;

    #[Test]
    public function test_index_user(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.users.index'));

        $response->assertOk();
    }

    #[Test]
    public function test_index_user_fail(): void
    {
        $this->actingAs($this->user2);

        $response = $this->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    #[Test]
    public function test_show_user(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.users.show', $this->user2->id));

        $response->assertOk();
    }

    #[Test]
    public function test_edit_user(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.users.edit', $this->user2->id));

        $response->assertOk();
    }

    #[Test]
    public function test_update_user():void
    {
        $this->actingAs($this->user);

        $token = Str::random(40);
        $this->withSession(['_token' => $token]);

        $requestData = [
            'name' => 'john doe',
            'email' => 'johndoe@google.com',
            '_token' => $token,
        ];

        $response = $this->patch(route('admin.users.update', $this->user2->id), $requestData);

        $response->assertRedirectToRoute('admin.users.index');

        array_pop($requestData);

        $this->assertDatabaseHas('users', $requestData);
    }

    #[Test]
    public function test_create_user(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.users.create'));

        $response->assertOk();
    }

    #[Test]
    public function test_store_user(): void
    {
        $this->actingAs($this->user);

        $token = Str::random(40);
        $this->withSession(['_token' => $token]);

        $requestData = [
            'name' => 'john doe',
            'email' => 'johndoe@google.com',
            'password' => 'password',
            '_token' => $token,
        ];

        $response = $this->post(route('admin.users.store'), $requestData);

        $response->assertRedirect(route('admin.users.index'));

        array_pop($requestData);
        array_pop($requestData);

        $this->assertDatabaseHas('users', $requestData);
    }

    #[Test]
    public function test_resetPassword_user(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.users.reset-password', $this->user2->id));

        $response->assertOk();
    }

    #[Test]
    public function test_updatePassword_user(): void
    {
        $this->actingAs($this->user);

        $token = Str::random(40);
        $this->withSession(['_token' => $token]);

        $requestData = [
            'password' => '123456789',
            '_token' => $token,
        ];

        $response = $this->put(route('admin.users.updatePassword', $this->user2->id), $requestData);

        $response->assertRedirectToRoute('admin.users.index');

        $user = User::query()->find($this->user2->id);

        $this->assertTrue(Hash::check('123456789',  $user->password));
    }

    public function test_destroy_user(): void
    {
        $this->actingAs($this->user);

        $token = Str::random(40);
        $this->withSession(['_token' => $token]);

        $requestData = [
            '_token' => $token,
        ];

        $response = $this->delete(route('admin.users.destroy', $this->user2->id), $requestData);

        $response->assertRedirectToRoute('admin.users.index');

        $this->assertDatabaseMissing('users', ['id' => $this->user2->id]);
    }

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
}
