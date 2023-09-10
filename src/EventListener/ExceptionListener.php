<?php

namespace App\EventListener;

use App\Exception\NotFoundException;
use App\Schema\AbstractAPIResponseSchema;
use App\Schema\NotFoundResponseSchema;
use App\Schema\UnAuthenticateResponseSchema;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedException) {
            $responseSchema = new UnAuthenticateResponseSchema();

            $response = new JsonResponse(
                $responseSchema
                    ->setFailedStatus()
                    ->setMessage($exception->getMessage())
                    ->setStatusCode(Response::HTTP_UNAUTHORIZED),
                Response::HTTP_UNAUTHORIZED
            );

            $event->setResponse($response);
        }

        if ($exception instanceof NotFoundException) {
            $responseSchema = new NotFoundResponseSchema();

            $response = new JsonResponse(
                $responseSchema
                    ->setFailedStatus()
                    ->setMessage($exception->getMessage())
                    ->setStatusCode(Response::HTTP_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );

            $event->setResponse($response);
        }
    }
}