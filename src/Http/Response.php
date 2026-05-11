<?php

namespace Velt\Http;

class Response implements ResponseInterface
{
    protected int $statusCode;
    protected array $headers = [];
    protected string $body;

    public function __construct(
        string $body = '',
        int $statusCode = 200,
        array $headers = []
    ) {
        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Factory: crée une réponse HTML
     */
    public static function html(string $html, int $statusCode = 200): self
    {
        $response = new self($html, $statusCode);
        $response->header('Content-Type', 'text/html; charset=utf-8');
        return $response;
    }

    /**
     * Définit un header
     */
    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this; // permet le chaînage
    }

    /**
     * Définit ou récupère le code de statut
     * Getter: $response->status()
     * Setter: $response->status(201)
     */
    public function status(int|null $code = null): int|self
    {
        if ($code === null) {
            return $this->statusCode;
        }
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Récupère tous les headers
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Récupère le contenu de la réponse
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * Envoie la réponse au client
     */
    public function send(): void
    {
        // Envoie le code de statut
        http_response_code($this->statusCode);

        // Envoie les headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Envoie le contenu
        echo $this->body;
    }
}