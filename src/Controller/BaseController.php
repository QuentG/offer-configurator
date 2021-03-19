<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends AbstractController
{
    protected const SUCCESS = 'success';
    protected const ERROR = 'error';

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @internal
     * @required
     */
    public function setEm(EntityManagerInterface $em): ?EntityManagerInterface
    {
        $previous = $this->em;
        $this->em = $em;

        return $previous;
    }

    protected function respond(string $message, $data = [], int $httpCode = JsonResponse::HTTP_OK, string $status = self::SUCCESS): JsonResponse
    {
        return new JsonResponse([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $httpCode);
    }

    protected function respondWithError(string $message, $data = [], int $httpCode = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return $this->respond($message, $data, $httpCode, self::ERROR);
    }

    protected function getFormErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $errors;
    }
}