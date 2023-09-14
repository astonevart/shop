<?php

namespace App\Tests\Unit\Factory;

use App\Factory\UserInfoDtoFactory;
use PHPUnit\Framework\TestCase;

class UserInfoDtoFactoryTest extends TestCase
{
    private UserInfoDtoFactory $userInfoDtoFactory;

    protected function setUp(): void
    {
        $this->userInfoDtoFactory = new UserInfoDtoFactory();
    }

    public function testCreate(): void
    {
        $createdAt = new \DateTimeImmutable();
        $userInfoDto = $this->userInfoDtoFactory->create('ip', 'user_agent', $createdAt);

        $this->assertSame('ip', $userInfoDto->ip);
        $this->assertSame('user_agent', $userInfoDto->userAgent);
        $this->assertSame(md5('ip_user_agent'), $userInfoDto->identifier);
        $this->assertSame($createdAt, $userInfoDto->createdAt);
    }
}