<?php

namespace App\Tests\Unit;

use App\Entity\UserInfo;
use App\Message\UserInfoMessage;
use App\MessageHandler\UserInfoHandler;
use App\Repository\UserInfoRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class UserInfoHandlerTest extends TestCase
{
    use ProphecyTrait;

    private EntityManagerInterface|ObjectProphecy $em;

    private UserInfoRepository|ObjectProphecy $userInfoRepository;

    private UserInfoHandler $userInfoHandler;

    protected function setUp(): void
    {
        $this->em = $this->prophesize(EntityManagerInterface::class);
        $this->userInfoRepository = $this->prophesize(UserInfoRepository::class);

        $this->userInfoHandler = new UserInfoHandler(
            $this->em->reveal(),
            $this->userInfoRepository->reveal()
        );
    }

    public function testInvoke_UserInfoCreate(): void
    {
        $this->userInfoRepository->findOneBy(['identifier' => md5(sprintf('%s_%s', '1.1.1.1', 'Safari'))])->shouldBeCalled()->willReturn(null);

        $this->em->persist(Argument::that(function (UserInfo $userInfo) {
            $this->assertSame('1.1.1.1', $userInfo->getIp());
            $this->assertSame('Safari', $userInfo->getUserAgent());
            $this->assertNull($userInfo->getUpdatedAt());

            return true;
        }))->shouldBeCalled();
        $this->em->flush()->shouldBeCalled();

        $this->userInfoHandler->__invoke(new UserInfoMessage('1.1.1.1', 'Safari'));
    }

    public function testInvoke_UserInfoUpdate(): void
    {
        $userInfo = new UserInfo();
        $userInfo->setIp('1.1.1.1');
        $userInfo->setUserAgent('Safari');
        $this->userInfoRepository->findOneBy(['identifier' => md5(sprintf('%s_%s', '1.1.1.1', 'Safari'))])->shouldBeCalled()->willReturn($userInfo);

        $this->em->flush()->shouldBeCalled();

        $this->userInfoHandler->__invoke(new UserInfoMessage('1.1.1.1', 'Safari'));

        $this->assertNotNull($userInfo->getUpdatedAt());
    }
}