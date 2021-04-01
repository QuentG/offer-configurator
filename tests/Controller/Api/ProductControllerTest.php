<?php

namespace App\Tests\Controller\Api;

use App\Controller\BaseController;
use App\Entity\Product;
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

    public function testProductChildrens(): void
    {
        $productRepository = static::$container->get(ProductRepository::class);
        $mainProduct = $productRepository->findOneBy([
            'parentId' => null,
            'type' => Product::PARENT_TYPE
        ]);

        $this->client->request(
            Request::METHOD_GET,
            sprintf('/api/products/%s/childrens/%s', $mainProduct->getId(), json_encode(['M']))
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}