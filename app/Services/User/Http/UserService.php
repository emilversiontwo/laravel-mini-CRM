<?php

namespace App\Services\User\Http;

use App\Models\User;
use App\Services\User\Dto\DestroyUserDto;
use App\Services\User\Dto\StoreUserDto;
use App\Services\User\Dto\UpdatePasswordUserDto;
use App\Services\User\Dto\UpdateUserDto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    /**
     * @return Collection
     */
    public function index(): Collection
    {
        return User::query()->get();
    }

    /**
     * @param UpdateUserDto $dto
     * @return User
     */
    public function update(UpdateUserDto $dto): User
    {
        $user = $dto->user->refresh();

        $user->name = $dto->name;
        $user->email = $dto->email;

        $user->save();

        return $user;
    }

    /**
     * @param StoreUserDto $dto
     * @return User
     */
    public function store(StoreUserDto $dto): User
    {
        $user = new User();

        $user->name = $dto->name;
        $user->email = $dto->email;
        $user->password = Hash::make($dto->password);

        $user->save();

        $role = Role::query()->where('name', '=', 'manager')->first();

        $user->assignRole($role);

        return $user;
    }

    /**
     * @param UpdatePasswordUserDto $dto
     * @return User
     */
    public function updatePassword(UpdatePasswordUserDto $dto): User
    {
        $user = $dto->user->refresh();

        $user->password = Hash::make($dto->password);
        $user->save();

        return $user;
    }

    public function destroy(DestroyUserDto $dto):void
    {
        $dto->user->delete();
    }
}
