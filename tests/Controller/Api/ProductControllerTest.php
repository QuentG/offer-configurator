<?php

namespace App\Tests\Controller\Api;

use App\Tests\ApiTestCase;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends ApiTestCase
{
    public function testProductsAll(): void
    {
        $this->client->request(Request::METHOD_GET, '/api/products');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testProductsIndex(): void
    {
        $productRepository = static::$container->get(ProductRepository::class);
        $product = $productRepository->getSingleProduct();

        $this->client->request(Request::METHOD_GET, '/api/products/' . $product->getId());
        // Check response code
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}