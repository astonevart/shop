<?php

namespace App\MessageHandler;

use App\Entity\UserInfo;
use App\Message\UserInfoMessage;
use App\Repository\UserInfoRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserInfoHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserInfoRepository $userInfoRepository
    ) {}

    public function __invoke(UserInfoMessage $message): void
    {
        $identifier = md5(sprintf('%s_%s', $message->getIp(), $message->getUserAgent()));

        if (!$userInfo = $this->userInfoRepository->findOneBy(['identifier' => $identifier])) {
            $userInfo = new UserInfo();
            $userInfo->setUserAgent($message->getUserAgent());
            $userInfo->setIp($message->getIp());
            $userInfo->setIdentifier($identifier);

            $this->em->persist($userInfo);
        } else {
            $userInfo->setUpdatedAtNow();
        }

        $this->em->flush();
    }
}