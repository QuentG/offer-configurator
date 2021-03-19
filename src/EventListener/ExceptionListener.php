<?php

namespace App\EventListener;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$event->getThrowable() instanceof NotFoundHttpException) {
            return;
        }

        // Sends the modified response object to the event
        $event->setResponse(new JsonResponse([
            'status' => BaseController::ERROR,
            'message' => 'route_not_found',
            'data' => []
        ], JsonResponse::HTTP_NOT_FOUND));
    }
}