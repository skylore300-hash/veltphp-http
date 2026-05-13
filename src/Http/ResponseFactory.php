<?php

namespace Velt\Http;

use Throwable;

class ResponseFactory
{
	public function fromResult(mixed $result, bool $isApiRoute): ResponseInterface
	{
		if ($result instanceof ResponseInterface) {
			return $result;
		}

		if ($result instanceof JsonableInterface && $isApiRoute) {
			return JsonResponse::json($result->toJson());
		}

		if (is_array($result) && $isApiRoute) {
			return JsonResponse::json($result);
		}

		if ($result instanceof RenderableInterface) {
			return Response::html($result->render());
		}

		if (is_string($result)) {
			return Response::html($result);
		}

		throw new HttpException(500, 'Invalid controller response');
	}

	public function fromException(Throwable $exception, bool $isApiRoute): ResponseInterface
	{
		$status = 500;
		$message = 'Server Error';

		if ($exception instanceof HttpException) {
			$status = $exception->statusCode();
			$message = $exception->getMessage() !== ''
				? $exception->getMessage()
				: $this->defaultMessage($status);
		}

		if ($isApiRoute) {
			return JsonResponse::json(['error' => $message], $status);
		}

		$safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
		$html = '<h1>' . $status . '</h1><p>' . $safeMessage . '</p>';

		return Response::html($html, $status);
	}

	private function defaultMessage(int $status): string
	{
		return match ($status) {
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			default => 'HTTP Error',
		};
	}
}