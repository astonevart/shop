<?php

namespace App\MessageHandler;

use App\Factory\UserInfoDtoFactory;
use App\Message\UserInfoMessage;
use App\Repository\UserInfoRepository;
use Symfony\Component\Messenger\Handler\Acknowledger;
use Symfony\Component\Messenger\Handler\BatchHandlerInterface;
use Symfony\Component\Messenger\Handler\BatchHandlerTrait;

class UserInfoBatchHandler implements BatchHandlerInterface
{
    use BatchHandlerTrait;

    public function __construct(
        private readonly UserInfoRepository $userInfoRepository,
        private readonly UserInfoDtoFactory $userInfoDtoFactory
    ) {}

    public function __invoke(UserInfoMessage $message, Acknowledger $ack = null): mixed
    {
        return $this->handle($message, $ack);
    }

    private function process(array $jobs): void
    {
        $result = [];
        foreach ($jobs as [$message, $ack]) {
            try {
                $result[] = $this->userInfoDtoFactory->create(
                    $message->getIp(),
                    $message->getUserAgent(),
                    $message->getCreatedAt()
                );

                $ack->ack($message);
            } catch (\Throwable $e) {
                $ack->nack($e);
            }
        }

        $this->userInfoRepository->insertOrUpdate($result);
    }
}