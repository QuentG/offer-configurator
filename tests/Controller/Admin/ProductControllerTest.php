<?php

namespace App\Tests\Controller\Admin;

use App\Tests\ApiTestCase;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends ApiTestCase
{
    protected const DIR_FIXTURES = './tests/Fixtures/';

    public function testGetProducts(): void
    {
        $this->client->request('GET', '/api/products');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetProduct(): void
    {
        $productRepository = static::$container->get(ProductRepository::class);
        $product = $productRepository->getSingleProduct();

        $this->client->request('GET', '/api/products/' . $product->getId());
        $this->assertResponseStatusCodeSame(200);
    }
}
