<?php

namespace Velt\Http;

class Route
{
    private string $method;
    private string $path;
    private string $regex;
    private array $paramNames;
    private mixed $handler;
    private array $middlewares = [];
    private bool $isApi;

    public function __construct(string $method, string $path, mixed $handler)
    {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->handler = $handler;
        $this->isApi = str_starts_with($path, '/api');

        // Précompile le pattern pour accélérer les matches.
        [$regex, $paramNames] = $this->compilePath($path);
        $this->regex = $regex;
        $this->paramNames = $paramNames;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function handler(): mixed
    {
        return $this->handler;
    }

    public function middleware(mixed $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function middlewares(): array
    {
        return $this->middlewares;
    }

    public function isApi(): bool
    {
        return $this->isApi;
    }

    /**
     * @return array<string, string>|null
     */
    public function matchPath(string $path): ?array
    {
        if (!preg_match($this->regex, $path, $matches)) {
            return null;
        }

        // Extrait uniquement les paramètres nommés du match regex.
        $params = [];
        foreach ($this->paramNames as $name) {
            $params[$name] = $matches[$name];
        }

        return $params;
    }

    /**
     * @return array{0:string,1:array<int,string>}
     */
    private function compilePath(string $path): array
    {
        $paramNames = [];
        // Échappe le chemin puis remplace les tokens {param} par des groupes regex.
        $pattern = preg_quote($path, '#');

        $pattern = preg_replace_callback(
            '/\\\\\\{([a-zA-Z_][a-zA-Z0-9_-]*)\\\\\\}/',
            function (array $matches) use (&$paramNames): string {
                $paramNames[] = $matches[1];
                return '(?P<' . $matches[1] . '>[^/]+)';
            },
            $pattern
        );

        return ['#^' . $pattern . '$#', $paramNames];
    }
}