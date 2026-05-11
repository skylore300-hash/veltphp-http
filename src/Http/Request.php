<?php

namespace Velt\Http;

class Request
{
    private string $method;
    private string $path;
    private array $query;
    private array $post;
    private array $server;
    private array $headers;

    public function __construct(
        string $method,
        string $path,
        array $query = [],
        array $post = [],
        array $server = [],
        array $headers = []
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->query = $query;
        $this->post = $post;
        $this->server = $server;
        $this->headers = $headers;
    }

    /**
     * Factory: crée une requête depuis les superglobales PHP
     */
    public static function capture(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = self::extractPath($_SERVER['REQUEST_URI'] ?? '/');

        return new self(
            $method,
            $path,
            $_GET ?? [],
            $_POST ?? [],
            $_SERVER ?? [],
            self::extractHeaders($_SERVER ?? [])
        );
    }

    /**
     * Extrait le chemin de REQUEST_URI (enlève la query string)
     */
    private static function extractPath(string $requestUri): string
    {
        $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';
        return $path === '' ? '/' : $path;
    }

    /**
     * Extrait les headers HTTP depuis $_SERVER
     */
    private static function extractHeaders(array $server): array
    {
        $headers = [];
        foreach ($server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = str_replace('HTTP_', '', $key);
                $headerName = str_replace('_', '-', strtolower($headerName));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }

    /**
     * Récupère la méthode HTTP (GET, POST, etc)
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Récupère le chemin de la requête
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Récupère un paramètre query avec valeur par défaut optionnelle
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * Récupère une donnée POST/formulaire avec valeur par défaut optionnelle
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * Récupère tous les paramètres query
     */
    public function queries(): array
    {
        return $this->query;
    }

    /**
     * Récupère toutes les données d'entrée
     */
    public function inputs(): array
    {
        return $this->post;
    }

    /**
     * Récupère un header
     */
    public function header(string $key): ?string
    {
        return $this->headers[strtolower($key)] ?? null;
    }

    /**
     * Récupère tous les headers
     */
    public function allHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Vérifie si la requête est POST
     */
    public function isPost(): bool
    {
        return $this->method === 'POST';
    }

    /**
     * Vérifie si la requête est GET
     */
    public function isGet(): bool
    {
        return $this->method === 'GET';
    }
}