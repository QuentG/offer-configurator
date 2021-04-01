<?php

namespace App\Tests\EventListener;

use App\Controller\BaseController;
use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;

class ExceptionListenerTest extends ApiTestCase
{
    public function testRouteNotFound(): void
    {
        $response = $this->jsonRequest(Request::METHOD_GET, '/api/toto');

        $this->assertJsonEqualsToJson($response, BaseController::ERROR, 'route_not_found');
    }
}