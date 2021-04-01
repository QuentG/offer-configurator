<?php

namespace App\Tests\Controller\Api;

use App\Controller\BaseController;
use App\Repository\ProductRepository;
use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderControllerTest extends ApiTestCase
{
    public function testAddProduct(): void
    {
        $productRepository = static::$container->get(ProductRepository::class);
        $product = $productRepository->getSingleProduct();

        $response = $this->jsonRequest(Request::METHOD_POST, '/cart/add', [
            'id' => $product->getId(),
            'quantity' => '1'
        ]);

        $this->assertJsonEqualsToJson($response, BaseController::SUCCESS, 'cart_updated');
    }

    public function testGetCart(): void
    {
        $this->client->request(Request::METHOD_GET, '/api/cart');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
