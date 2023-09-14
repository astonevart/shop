<?php

namespace App\Message;

class UserInfoMessage
{
    public function __construct(
        private readonly string $ip,
        private readonly string $userAgent,
        private readonly \DateTimeImmutable $createdAt,
    ) {}

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}