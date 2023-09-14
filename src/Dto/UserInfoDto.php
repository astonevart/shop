<?php

namespace App\Dto;

class UserInfoDto
{
    public function __construct(
        public readonly string $ip,
        public readonly string $userAgent,
        public readonly string $identifier,
        public readonly \DateTimeImmutable $createdAt
    ) {}
}