<?php

namespace App\Tests\Unit;

use App\Message\UserInfoMessage;
use PHPUnit\Framework\TestCase;

class UserInfoMessageTest extends TestCase
{
    public function testGetters(): void
    {
        $createdAt = new \DateTimeImmutable();
        $userInfoMessage = new UserInfoMessage('ip', 'user_agent', $createdAt);

        $this->assertSame('ip', $userInfoMessage->getIp());
        $this->assertSame('user_agent', $userInfoMessage->getUserAgent());
        $this->assertSame($createdAt, $userInfoMessage->getCreatedAt());
    }
}