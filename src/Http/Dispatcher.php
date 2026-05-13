<?php

namespace Velt\Http;

use Throwable;

class Dispatcher
{
	private Router $router;
	private ?object $container;
	private ResponseFactory $responseFactory;

	public function __construct(
		Router $router,
		?object $container = null,
		?ResponseFactory $responseFactory = null
	) {
		$this->router = $router;
		$this->container = $container;
		$this->responseFactory = $responseFactory ?? new ResponseFactory();
	}

	public function dispatch(Request $request): ResponseInterface
	{
		$isApi = str_starts_with($request->path(), '/api');

		try {
			$match = $this->router->match($request);
			$route = $match['route'] ?? null;
			$handler = $match['handler'];
			$params = $match['params'];
			$isApi = $route ? $route->isApi() : $isApi;

			$core = function (Request $request) use ($handler, $params, $isApi): ResponseInterface {
				$result = $this->invokeHandler($handler, $params);
				return $this->responseFactory->fromResult($result, $isApi);
			};

			$pipeline = new Pipeline(
				$route?->middlewares() ?? [],
				$core,
				fn (mixed $middleware) => $this->resolveMiddleware($middleware)
			);

			return $pipeline->handle($request);
		} catch (Throwable $exception) {
			return $this->responseFactory->fromException($exception, $isApi);
		}
	}

	private function resolveMiddleware(mixed $middleware): MiddlewareInterface
	{
		if ($middleware instanceof MiddlewareInterface) {
			return $middleware;
		}

		if (is_string($middleware)) {
			$middleware = $this->resolveClass($middleware);
		}

		if (!$middleware instanceof MiddlewareInterface) {
			throw new HttpException(500, 'Invalid middleware');
		}

		return $middleware;
	}

	private function invokeHandler(mixed $handler, array $params): mixed
	{
		if (is_array($handler) && isset($handler[0], $handler[1]) && is_string($handler[0])) {
			$controller = $this->resolveClass($handler[0]);
			$handler = [$controller, $handler[1]];
		}

		if (!is_callable($handler)) {
			throw new HttpException(500, 'Invalid handler');
		}

		return call_user_func_array($handler, $params);
	}

	private function resolveClass(string $class): object
	{
		if ($this->container !== null) {
			if (method_exists($this->container, 'has') && $this->container->has($class)) {
				return $this->container->get($class);
			}

			if (method_exists($this->container, 'get')) {
				return $this->container->get($class);
			}
		}

		return new $class();
	}
}