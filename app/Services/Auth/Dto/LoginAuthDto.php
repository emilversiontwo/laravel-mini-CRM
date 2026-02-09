<?php

namespace App\Services\Auth\Dto;

use App\Helpers\Dto\Dto;

class LoginAuthDto extends Dto
{
    public string $email;

    public string $password;
}
