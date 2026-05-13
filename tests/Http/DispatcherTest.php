<?php

namespace Velt\Http\Tests;

use PHPUnit\Framework\TestCase;
use Velt\Http\Dispatcher;
use Velt\Http\JsonResponse;
use Velt\Http\MiddlewareInterface;
use Velt\Http\Request;
use Velt\Http\Response;
use Velt\Http\ResponseInterface;
use Velt\Http\Router;

class DispatcherTest extends TestCase
{
	public function testDispatchesCallableHandler(): void
	{
		$router = new Router();
		$router->get('/hello', function (): ResponseInterface {
			return Response::html('<h1>Hello</h1>');
		});

		$dispatcher = new Dispatcher($router);
		$response = $dispatcher->dispatch(new Request('GET', '/hello'));

		$this->assertSame(200, $response->status());
		$this->assertSame('<h1>Hello</h1>', $response->body());
	}

	public function testDispatchesControllerFromContainer(): void
	{
		$router = new Router();
		$router->get('/dashboard', [ContainerController::class, 'index']);

		$container = new FakeContainer([
			ContainerController::class => new ContainerController('from-container'),
		]);

		$dispatcher = new Dispatcher($router, $container);
		$response = $dispatcher->dispatch(new Request('GET', '/dashboard'));

		$this->assertSame('from-container', $response->body());
	}

	public function testMiddlewareCanBlockRequest(): void
	{
		$router = new Router();
		$controller = new FlagController();
		$router->get('/admin', [$controller, 'index'])
			->middleware(BlockingMiddleware::class);

		$dispatcher = new Dispatcher($router);
		$response = $dispatcher->dispatch(new Request('GET', '/admin'));

		$this->assertSame(403, $response->status());
		$this->assertFalse($controller->called);
	}

	public function testMiddlewareCanPassRequest(): void
	{
		$router = new Router();
		$controller = new FlagController();
		$middleware = new PassThroughMiddleware();

		$router->get('/profile', [$controller, 'index'])
			->middleware($middleware);

		$dispatcher = new Dispatcher($router);
		$response = $dispatcher->dispatch(new Request('GET', '/profile'));

		$this->assertTrue($middleware->called);
		$this->assertSame('ok', $response->body());
	}

	public function testArrayReturnBecomesJsonForApiRoute(): void
	{
		$router = new Router();
		$router->get('/api/items', function (): array {
			return ['ok' => true];
		});

		$dispatcher = new Dispatcher($router);
		$response = $dispatcher->dispatch(new Request('GET', '/api/items'));

		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertSame('{"ok":true}', $response->body());
		$this->assertSame('application/json', $response->headers()['Content-Type']);
	}

	public function testDispatchReturns404ResponseWhenRouteMissing(): void
	{
		$router = new Router();
		$dispatcher = new Dispatcher($router);

		$response = $dispatcher->dispatch(new Request('GET', '/missing'));

		$this->assertSame(404, $response->status());
		$this->assertStringContainsString('Not Found', $response->body());
	}

	public function testDispatchReturnsJsonErrorForApiRoute(): void
	{
		$router = new Router();
		$dispatcher = new Dispatcher($router);

		$response = $dispatcher->dispatch(new Request('GET', '/api/missing'));

		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertSame(404, $response->status());
		$this->assertSame('application/json', $response->headers()['Content-Type']);
	}
}

class FakeContainer
{
	/** @var array<string, object> */
	private array $entries;

	/** @param array<string, object> $entries */
	public function __construct(array $entries)
	{
		$this->entries = $entries;
	}

	public function has(string $id): bool
	{
		return array_key_exists($id, $this->entries);
	}

	public function get(string $id): object
	{
		return $this->entries[$id];
	}
}

class ContainerController
{
	private string $label;

	public function __construct(string $label)
	{
		$this->label = $label;
	}

	public function index(): ResponseInterface
	{
		return Response::html($this->label);
	}
}

class FlagController
{
	public bool $called = false;

	public function index(): ResponseInterface
	{
		$this->called = true;
		return Response::html('ok');
	}
}

class BlockingMiddleware implements MiddlewareInterface
{
	public function handle(Request $request, callable $next): ResponseInterface
	{
		return Response::html('blocked', 403);
	}
}

class PassThroughMiddleware implements MiddlewareInterface
{
	public bool $called = false;

	public function handle(Request $request, callable $next): ResponseInterface
	{
		$this->called = true;
		return $next($request);
	}
}
