<?php

namespace App\Services\User\Dto;

use App\Helpers\Dto\Dto;

class StoreUserDto extends Dto
{
    public string $name;

    public string $email;

    public string $password;
}
