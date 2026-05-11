<?php

namespace Velt\Http;

class JsonResponse extends Response implements ResponseInterface
{
    /**
     * Factory: crée une réponse JSON
     */
    public static function json(mixed $data, int $statusCode = 200): self
    {
        $response = new self(json_encode($data), $statusCode);
        $response->header('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Crée une réponse 201 Created avec données
     */
    public static function created(mixed $data): self
    {
        return self::json($data, 201);
    }

    /**
     * Crée une réponse 204 No Content
     */
    public static function noContent(): self
    {
        return new self('', 204);
    }
}