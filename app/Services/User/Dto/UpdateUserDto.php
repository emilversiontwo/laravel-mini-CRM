<?php

namespace App\Services\User\Dto;

use App\Helpers\Dto\Dto;
use App\Models\User;

class UpdateUserDto extends Dto
{
    public User $user;

    public string $name;

    public string $email;
}
