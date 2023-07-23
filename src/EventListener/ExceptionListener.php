<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException) {
            $data = ['error' => $exception->getMessage()];
            $response = new JsonResponse($data);
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->headers->replace($exception->getHeaders());
            $event->setResponse($response);

            return;
        }
        if ($exception instanceof \InvalidArgumentException || $exception instanceof NotNullConstraintViolationException) {
            $data = ['error' => $exception->getMessage()];
            $response = new JsonResponse($data);
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);

            return;
        }

        $data = ['error' => 'Internal Server Error ??'];
        $response = new JsonResponse($data);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        $event->setResponse($response);
    }
}
