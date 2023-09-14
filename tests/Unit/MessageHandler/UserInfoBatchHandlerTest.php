<?php

namespace App\Tests\Unit\MessageHandler;

use App\Dto\UserInfoDto;
use App\Factory\UserInfoDtoFactory;
use App\Message\UserInfoMessage;
use App\MessageHandler\UserInfoBatchHandler;
use App\Repository\UserInfoRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class UserInfoBatchHandlerTest extends TestCase
{
    use ProphecyTrait;

    private UserInfoRepository|ObjectProphecy $userInfoRepository;

    private UserInfoDtoFactory|ObjectProphecy $userInfoDtoFactory;

    protected function setUp(): void
    {
        $this->userInfoDtoFactory = $this->prophesize(UserInfoDtoFactory::class);
        $this->userInfoRepository = $this->prophesize(UserInfoRepository::class);

        $this->userInfoHandler = new UserInfoBatchHandler(
            $this->userInfoRepository->reveal(),
            $this->userInfoDtoFactory->reveal()
        );
    }

    public function testInvoke(): void
    {
        $createdAt = new \DateTimeImmutable();
        $userInfoDto = new UserInfoDto('ip', 'user_agent', 'identifier', $createdAt);
        $this->userInfoDtoFactory->create('ip', 'user_agent', $createdAt)->shouldBeCalled()->willReturn($userInfoDto);
        $this->userInfoRepository->insertOrUpdate([$userInfoDto])->shouldBeCalled();
        $message = new UserInfoMessage('ip', 'user_agent', $createdAt);

        $this->userInfoHandler->__invoke($message);
    }
}