<?php

namespace App\Services\User\Dto;

use App\Helpers\Dto\Dto;
use App\Models\User;

class UpdatePasswordUserDto extends Dto
{
    public User $user;

    public string $password;
}
