<?php

namespace Velt\Http\Tests;

use PHPUnit\Framework\TestCase;
use Velt\Http\HttpException;
use Velt\Http\Request;
use Velt\Http\Router;

class RouterTest extends TestCase
{
    public function testMatchesStaticRoute(): void
    {
        // Route statique: retour handler + params vides.
        $router = new Router();
        $router->get('/', ['HomeController', 'index']);

        $request = new Request('GET', '/');

        $result = $router->match($request);

        $this->assertSame(['HomeController', 'index'], $result['handler']);
        $this->assertSame([], $result['params']);
    }

    public function testMatchesDynamicRouteAndExtractsParams(): void
    {
        // Route dynamique: extraction du paramètre {id}.
        $router = new Router();
        $router->get('/api/preview/{id}', ['PreviewController', 'show']);

        $request = new Request('GET', '/api/preview/demo123');

        $result = $router->match($request);

        $this->assertSame('demo123', $result['params']['id']);
    }

    public function testThrows404WhenNoRouteMatches(): void
    {
        // Aucun chemin ne match -> 404.
        $router = new Router();
        $router->get('/exists', ['Controller', 'index']);

        $request = new Request('GET', '/missing');

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(404);

        $router->match($request);
    }

    public function testThrows405WhenPathMatchesButMethodNotAllowed(): void
    {
        // Chemin match, méthode non autorisée -> 405.
        $router = new Router();
        $router->post('/login', ['AuthController', 'login']);

        $request = new Request('GET', '/login');

        $this->expectException(HttpException::class);
        $this->expectExceptionCode(405);

        $router->match($request);
    }
}