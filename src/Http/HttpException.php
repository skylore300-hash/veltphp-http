<?php

namespace Velt\Http;

use RuntimeException;

class HttpException extends RuntimeException
{
    private int $statusCode;

    public function __construct(int $statusCode, string $message = '')
    {
        // Stocke le status à part pour l'exposer sans parser message/code.
        parent::__construct($message !== '' ? $message : 'HTTP Error', $statusCode);
        $this->statusCode = $statusCode;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }
}