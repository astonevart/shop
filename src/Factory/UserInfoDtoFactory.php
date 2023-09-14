<?php

namespace App\Factory;

use App\Dto\UserInfoDto;

class UserInfoDtoFactory
{
    public function create(
        string $ip,
        string $userAgent,
        \DateTimeImmutable $createdAt,
    ): UserInfoDto {
        return new UserInfoDto(
            $ip,
            $userAgent,
            md5(sprintf('%s_%s', $ip, $userAgent)),
            $createdAt
        );
    }
}