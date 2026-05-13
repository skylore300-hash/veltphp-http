<?php

namespace Velt\Http;

class PhpSessionStore implements SessionStoreInterface
{
    public function __construct()
    {
        $this->ensureStarted();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $this->ensureStarted();
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        $this->ensureStarted();
        return array_key_exists($key, $_SESSION);
    }

    public function remove(string $key): void
    {
        $this->ensureStarted();
        unset($_SESSION[$key]);
    }

    private function ensureStarted(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
