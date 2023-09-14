<?php

namespace App\Factory;

use App\Message\UserInfoMessage;

class UserInfoMessageFactory
{
    public function create(string $ip, string $userAgent, \DateTimeImmutable $createdAt): UserInfoMessage
    {
        return new UserInfoMessage($ip, $userAgent, $createdAt);
    }
}