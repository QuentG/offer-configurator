<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    protected const BASE_URL = "/api";

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
    }

    public function loadRepository($repository): ?object
    {
        return static::$container->get($repository);
    }

    public function jsonRequest(string $method, string $url, ?array $data = null): string
    {
        $headers = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Accept' => 'application/json'
        ];

        $this->client->request($method, self::BASE_URL . $url, [], [], $headers, $data ? json_encode($data) : null);

        return $this->client->getResponse()->getContent();
    }

    public function assertJsonEqualsToJson(string $response, string $status, string $message, array $data = []): void
    {
        $json = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        self::assertJsonStringEqualsJsonString($response, json_encode($json));
    }

    protected function assertHasValidationErrors(object $entity, int $number = 0): void
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($entity);
        self::assertCount($number, $errors);
        $this->tearDown(); // To handle multiple asserts in a row
    }
}
