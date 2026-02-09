<?php

namespace App\Services\User\Dto;

use App\Helpers\Dto\Dto;
use App\Models\User;

class DestroyUserDto extends Dto
{
    public User $user;
}
