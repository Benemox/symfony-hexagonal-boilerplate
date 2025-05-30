<?php

namespace App\Shared\Infrastructure\Symfony\Controller;

use App\Shared\Domain\Bus\CommandMessageInterface;
use App\Shared\Domain\Bus\QueryMessageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractAPIController extends AbstractController
{
    public function __construct(
        protected MessageBusInterface $commandBus,
        protected MessageBusInterface $queryBus
    ) {
    }

    protected function dispatch(object $message): Envelope
    {
        return match (true) {
            $message instanceof QueryMessageInterface => $this->queryBus->dispatch($message),
            $message instanceof CommandMessageInterface => $this->commandBus->dispatch($message),
            default => throw new \InvalidArgumentException(sprintf(
                'The message %s does not implement the required interface.',
                $message::class
            )),
        };
    }


    protected function isRoleAccessGranted(string $role)
    {
        if (!$this->isGranted($role)) {
            throw $this->createAccessDeniedException('Access Denied');
        }
    }


    protected function success(array $data, int $statusCode = 200): JsonResponse
    {
        return new JsonResponse($data, $statusCode);
    }


    protected function error(string $message, int $statusCode = 400): JsonResponse
    {
        return new JsonResponse(['error' => $message], $statusCode);
    }
}
