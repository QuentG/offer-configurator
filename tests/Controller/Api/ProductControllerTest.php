<?php

namespace App\Tests\Controller\Api;

use App\Tests\ApiTestCase;
use PHPUnit\Framework\TestCase;
use App\Repository\ProductRepository;

class ProductControllerTest extends ApiTestCase
{
    public function testProductsAll(): void
    {
        $this->client->request('GET', '/api/products');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testProductsIndex(): void
    {
        $productRepository = static::$container->get(ProductRepository::class);
        $product = $productRepository->getSingleProduct();

        $this->client->request('GET', '/api/products/' . $product->getId());
        $this->assertResponseStatusCodeSame(404);
    }
}