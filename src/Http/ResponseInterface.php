<?php

namespace Velt\Http;

interface ResponseInterface
{
    /**
     * Définit ou récupère le code de statut HTTP
     */
    public function status(int|null $code = null): int|self;

    /**
     * Récupère tous les headers
     * 
     * @return array<string, string>
     */
    public function headers(): array;

    /**
     * Récupère le contenu de la réponse
     */
    public function body(): string;

    /**
     * Envoie la réponse au client
     */
    public function send(): void;
}