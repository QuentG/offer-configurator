<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApiTestCase extends WebTestCase
{
    protected const BASE_TOKEN = 'a54w4de4s51f484v5c1qc';
    protected const DIR_FIXTURES = './tests/Fixtures/';

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
    }

    public function loginAs(User $user): void
    {
        $this->simulateLogin($user);
    }

    public function loadRepository($repository)
    {
        return static::$container->get($repository);
    }

    public function jsonRequest(string $method, string $url, ?array $data = null): string
    {
        $headers = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Accept' => 'application/json'
        ];

        $this->client->request($method, $url, [], [], $headers, $data ? json_encode($data) : null);

        return $this->client->getResponse()->getContent();
    }

    public function assertJsonEqualsToJson(string $response, string $status, string $message, array $data = []): void
    {
        $json = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        $this->assertJsonStringEqualsJsonString($response, json_encode($json));
    }

    protected function assertHasValidationErrors(object $entity, int $number = 0): void
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($entity);
        $this->assertCount($number, $errors);
        self::tearDown(); // To handle multiple asserts in a row
    }
}
