<?php

namespace App\Controller;

use App\Message\UserInfoMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserInfoController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $bus)
    {}

    #[Route('/collect', name: 'collect_user_info')]
    public function index(Request $request): JsonResponse
    {
        if (!$ip = $request->getClientIp()) {
            return $this->json(['OK']);
        }

        if (!$userAgent = $request->headers->get('User-Agent')) {
            return $this->json(['OK']);
        }

        try {
            $this->bus->dispatch(new UserInfoMessage($ip, $userAgent));
        } catch (\Throwable $t) {
            return $this->json([], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['OK']);
    }
}
