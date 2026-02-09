<?php

namespace App\Services\Auth\Http;

use App\Exceptions\AppLogicException;
use App\Models\User;
use App\Services\Auth\Dto\LoginAuthDto;
use App\Services\Auth\Dto\LogoutAuthDto;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class AuthService
{
    /**
     * @throws AppLogicException
     */
    public function login(LoginAuthDto $dto): string
    {
        $user = User::query()->where('email', $dto->email)->first();

        if (!Hash::check($dto->password, $user->password)) {
            throw new AppLogicException('email or password is wrong', ResponseCode::HTTP_UNAUTHORIZED);
        }

        return $user->createToken('token')->plainTextToken;
    }

    /**
     * @param LogoutAuthDto $dto
     * @return void
     */
    public function logout(LogoutAuthDto $dto): void
    {
        $user = $dto->user->refresh();

        $user->currentAccessToken()->delete();
    }
}
