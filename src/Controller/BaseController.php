<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseController extends AbstractController
{
    public const SUCCESS = 'success';
    public const ERROR = 'error';

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

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

    /**
     * @internal
     * @required
     */
    public function setSerializer(SerializerInterface $serializer): ?SerializerInterface
    {
        $previous = $this->serializer;
        $this->serializer = $serializer;

        return $previous;
    }

    protected function respond(string $message, $data = [], int $httpCode = JsonResponse::HTTP_OK, string $status = self::SUCCESS): JsonResponse
    {
        return new JsonResponse([
            'status' => $status,
            'message' => $message,
            'data' => $data
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