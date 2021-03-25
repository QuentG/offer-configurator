<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use App\Entity\Product;
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
        $simpleProducts = $this->productRepository->findBy([
            'parentId' => null,
            'type' => Product::PARENT_TYPE
        ]);

        $products = $this->serializer->serialize(
            $simpleProducts,
            'json',
            ['groups' => 'offer.read']
        );

        return $this->respond('all_products', json_decode($products));
    }

    #[Route('/{id}', name: 'index')]
    public function index(Product $product): JsonResponse
    {
        $serializedProduct = $this->serializer->serialize(
            $product,
            'json',
            ['groups' => 'offer.read']
        );

        return $this->respond(sprintf('product_%s', $product->getId()), json_decode($serializedProduct));
    }

    #[Route('/{id}/childrens/{options}', name: 'childrens')]
    public function childrens(Product $product, string $options): JsonResponse
    {
        $products = $this->serializer->serialize(
            $this->productRepository->getChildrens($product, json_decode($options)),
            'json',
            ['groups' => 'offer.read']
        );

        return $this->respond(sprintf('product_%s', $product->getId()), json_decode($products));
    }
}