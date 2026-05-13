<?php

namespace Velt\Http;

class Pipeline
{
	/** @var array<int, mixed> */
	private array $middlewares;
	private $handler;
	/** @var callable */
	private $resolver;

	/**
	 * @param array<int, mixed> $middlewares
	 */
	public function __construct(array $middlewares, callable $handler, callable $resolver)
	{
		$this->middlewares = $middlewares;
		$this->handler = $handler;
		$this->resolver = $resolver;
	}

	public function handle(Request $request): ResponseInterface
	{
		$pipeline = $this->handler;

		foreach (array_reverse($this->middlewares) as $middleware) {
			$next = $pipeline;
			$pipeline = function (Request $request) use ($middleware, $next): ResponseInterface {
				$resolver = $this->resolver;
				$instance = $resolver($middleware);

				if (!$instance instanceof MiddlewareInterface) {
					throw new HttpException(500, 'Invalid middleware');
				}

				return $instance->handle($request, $next);
			};
		}

		return $pipeline($request);
	}
}