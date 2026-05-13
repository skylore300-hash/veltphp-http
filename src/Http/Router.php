<?php

namespace Velt\Http;

class Router
{
    /** @var Route[] */
    private array $routes = [];

    public function get(string $path, mixed $handler): self
    {
        $this->routes[] = new Route('GET', $path, $handler);
        return $this;
    }

    public function post(string $path, mixed $handler): self
    {
        $this->routes[] = new Route('POST', $path, $handler);
        return $this;
    }

    /**
     * @return array{handler:mixed,params:array<string,string>}
     */
    public function match(Request $request): array
    {
        $path = $request->path();
        $method = strtoupper($request->method());

        // Première passe : collecte les routes qui matchent le chemin.
        $pathMatches = [];
        foreach ($this->routes as $route) {
            $params = $route->matchPath($path);
            if ($params !== null) {
                $pathMatches[] = [$route, $params];
            }
        }

        if ($pathMatches === []) {
            throw new HttpException(404, 'Not Found');
        }

        foreach ($pathMatches as [$route, $params]) {
            if ($route->method() === $method) {
                return ['handler' => $route->handler(), 'params' => $params];
            }
        }

        // Le chemin existe, mais la méthode HTTP n'est pas autorisée.
        throw new HttpException(405, 'Method Not Allowed');
    }
}