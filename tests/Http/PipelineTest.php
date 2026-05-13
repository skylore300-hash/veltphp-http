<?php

namespace Velt\Http\Tests;

use PHPUnit\Framework\TestCase;
use Velt\Http\MiddlewareInterface;
use Velt\Http\Pipeline;
use Velt\Http\Request;
use Velt\Http\Response;
use Velt\Http\ResponseInterface;

class PipelineTest extends TestCase
{
	public function testExecutesMiddlewaresInOrder(): void
	{
		$calls = [];

		$middlewareA = new class($calls) implements MiddlewareInterface {
			/** @var array<int, string> */
			private array $calls;

			/** @param array<int, string> $calls */
			public function __construct(array &$calls)
			{
				$this->calls = &$calls;
			}

			public function handle(Request $request, callable $next): ResponseInterface
			{
				$this->calls[] = 'a:before';
				$response = $next($request);
				$this->calls[] = 'a:after';
				return $response;
			}
		};

		$middlewareB = new class($calls) implements MiddlewareInterface {
			/** @var array<int, string> */
			private array $calls;

			/** @param array<int, string> $calls */
			public function __construct(array &$calls)
			{
				$this->calls = &$calls;
			}

			public function handle(Request $request, callable $next): ResponseInterface
			{
				$this->calls[] = 'b:before';
				$response = $next($request);
				$this->calls[] = 'b:after';
				return $response;
			}
		};

		$handler = function (Request $request) use (&$calls): ResponseInterface {
			$calls[] = 'handler';
			return Response::html('ok');
		};

		$pipeline = new Pipeline(
			[$middlewareA, $middlewareB],
			$handler,
			fn (mixed $middleware) => $middleware
		);

		$pipeline->handle(new Request('GET', '/'));

		$this->assertSame(
			['a:before', 'b:before', 'handler', 'b:after', 'a:after'],
			$calls
		);
	}

	public function testMiddlewareCanShortCircuit(): void
	{
		$calls = [];

		$blocking = new class($calls) implements MiddlewareInterface {
			/** @var array<int, string> */
			private array $calls;

			/** @param array<int, string> $calls */
			public function __construct(array &$calls)
			{
				$this->calls = &$calls;
			}

			public function handle(Request $request, callable $next): ResponseInterface
			{
				$this->calls[] = 'blocked';
				return Response::html('blocked', 403);
			}
		};

		$handler = function (Request $request) use (&$calls): ResponseInterface {
			$calls[] = 'handler';
			return Response::html('ok');
		};

		$pipeline = new Pipeline(
			[$blocking],
			$handler,
			fn (mixed $middleware) => $middleware
		);

		$response = $pipeline->handle(new Request('GET', '/'));

		$this->assertSame(403, $response->status());
		$this->assertSame(['blocked'], $calls);
	}
}
