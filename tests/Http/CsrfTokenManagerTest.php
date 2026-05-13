<?php

namespace Velt\Http\Tests;

use PHPUnit\Framework\TestCase;
use Velt\Http\CsrfTokenManager;
use Velt\Http\Request;
use Velt\Http\SessionStoreInterface;

class CsrfTokenManagerTest extends TestCase
{
	public function testGeneratesTokenAndField(): void
	{
		$store = new ArraySessionStore();
		$manager = new CsrfTokenManager($store);

		$token = $manager->token();

		$this->assertNotSame('', $token);
		$this->assertSame($token, $manager->token());

		$field = $manager->field();

		$this->assertStringContainsString('type="hidden"', $field);
		$this->assertStringContainsString($token, $field);
	}

	public function testValidatesPostRequestToken(): void
	{
		$store = new ArraySessionStore();
		$manager = new CsrfTokenManager($store);

		$token = $manager->token();
		$request = new Request('POST', '/submit', [], ['_csrf_token' => $token]);

		$this->assertTrue($manager->validateRequest($request));
	}

	public function testRejectsInvalidToken(): void
	{
		$store = new ArraySessionStore();
		$manager = new CsrfTokenManager($store);

		$manager->token();
		$request = new Request('POST', '/submit', [], ['_csrf_token' => 'invalid']);

		$this->assertFalse($manager->validateRequest($request));
	}
}

class ArraySessionStore implements SessionStoreInterface
{
	/** @var array<string, mixed> */
	private array $data = [];

	public function get(string $key, mixed $default = null): mixed
	{
		return $this->data[$key] ?? $default;
	}

	public function set(string $key, mixed $value): void
	{
		$this->data[$key] = $value;
	}

	public function has(string $key): bool
	{
		return array_key_exists($key, $this->data);
	}

	public function remove(string $key): void
	{
		unset($this->data[$key]);
	}
}
