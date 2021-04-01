<?php

namespace App\Tests\Controller\Admin;

use App\Tests\ApiTestCase;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends ApiTestCase
{
    protected const ADMIN_EMAIL = 'admin@gmail.com';

    public function testAdminAccess(): void
    {
        $userRepository = $this->loadRepository(UserRepository::class);
        $admin = $userRepository->findOneBy(['email' => self::ADMIN_EMAIL]);

        $this->client->loginUser($admin);

        $this->client->request(Request::METHOD_GET, '/admin/products');
        self::assertResponseIsSuccessful();
    }

    public function testInvalidAdminAccess(): void
    {
        $this->client->request(Request::METHOD_GET, '/admin/products');
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
