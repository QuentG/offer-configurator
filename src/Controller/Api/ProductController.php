<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products', name: 'products.')]
class ProductController extends BaseController
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    #[Route('', name: 'all')]
    public function all(): JsonResponse
    {
        $products = $this->serializer->serialize(
            $this->productRepository->findBy(['parentId' => null]),
            'json',
            ['groups' => 'offer.read']
        );

        return $this->respond('all_products', json_decode($products));
    }

    #[Route('/{id}', name: 'index')]
    public function index(int $id): JsonResponse
    {
        $products = $this->serializer->serialize(
            $this->productRepository->find($id),
            'json',
            ['groups' => 'offer.read']
        );

        return $this->respond(sprintf('product_%s', $id), json_decode($products));
    }

    #[Route('/{id}', name: 'index.options')]
    public function getWithOptions(int $id): JsonResponse
    {
        $products = $this->serializer->serialize(
            $this->productRepository->find($id),
            'json',
            ['groups' => 'offer.read']
        );

        return $this->respond(sprintf('product_%s', $id), json_decode($products));
    }
}