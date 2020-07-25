<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener.
 */
class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];

        $event->setResponse(new JsonResponse($response, Response::HTTP_INTERNAL_SERVER_ERROR));
    }
}