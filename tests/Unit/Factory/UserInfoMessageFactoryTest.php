<?php

namespace App\Tests\Unit\Factory;

use App\Factory\UserInfoMessageFactory;
use PHPUnit\Framework\TestCase;

class UserInfoMessageFactoryTest extends TestCase
{
    private UserInfoMessageFactory $userInfoMessageFactory;

    protected function setUp(): void
    {
        $this->userInfoMessageFactory = new UserInfoMessageFactory();
    }

    public function testCreate(): void
    {
        $createdAt = new \DateTimeImmutable();
        $userInfoMessage = $this->userInfoMessageFactory->create('ip', 'user_agent', $createdAt);

        $this->assertSame('ip', $userInfoMessage->getIp());
        $this->assertSame('user_agent', $userInfoMessage->getUserAgent());
        $this->assertSame($createdAt, $userInfoMessage->getCreatedAt());
    }
}