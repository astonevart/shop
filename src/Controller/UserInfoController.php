<?php

namespace App\Controller;

use App\Dto\UserRequestInfoDto;
use App\Factory\UserInfoMessageFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserInfoController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $bus)
    {}

    #[Route('/collect', name: 'collect_user_info')]
    public function collectUserInfo(
        #[MapRequestPayload] UserRequestInfoDto $userRequestInfoDto,
        UserInfoMessageFactory $userInfoMessageFactory
    ): JsonResponse {
        try {
            $this->bus->dispatch(
                $userInfoMessageFactory->create(
                    $userRequestInfoDto->ip,
                    $userRequestInfoDto->userAgent,
                    new \DateTimeImmutable()
                )
            );
        } catch (\Throwable $t) {
            return $this->json([], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['OK']);
    }
}
