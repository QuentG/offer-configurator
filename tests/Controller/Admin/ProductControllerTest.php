<?php

namespace App\Tests\Controller\Admin;

use App\Tests\ApiTestCase;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends ApiTestCase
{
    protected const DIR_FIXTURES = './tests/Fixtures/';
    protected const ADMIN_EMAIL = 'admin@gmail.com';

    public function testAdminAccess(): void
    {
        $userRepository = $this->loadRepository(UserRepository::class);
        $admin = $userRepository->findOneBy(['email' => self::ADMIN_EMAIL]);

        $this->client->loginUser($admin);

        $response = $this->client->request('GET', '/admin/products');
        $this->assertResponseIsSuccessful();
    }

    public function testInvalidAdminAccess(): void
    {
        $response = $this->client->request('GET', '/admin/products');
        $this->assertResponseStatusCodeSame(302);
    }
}
