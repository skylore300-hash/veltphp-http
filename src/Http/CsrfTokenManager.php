<?php

namespace Velt\Http;

class CsrfTokenManager
{
	private SessionStoreInterface $store;
	private string $sessionKey;
	private string $inputName;

	public function __construct(
		SessionStoreInterface $store,
		string $sessionKey = '_csrf_token',
		string $inputName = '_csrf_token'
	) {
		$this->store = $store;
		$this->sessionKey = $sessionKey;
		$this->inputName = $inputName;
	}

	/**
	 * Retourne le token actuel ou en genere un nouveau.
	 */
	public function token(): string
	{
		if ($this->store->has($this->sessionKey)) {
			return (string) $this->store->get($this->sessionKey);
		}

		$token = $this->generateToken();
		$this->store->set($this->sessionKey, $token);
		return $token;
	}

	/**
	 * Verifie la validite d'un token.
	 */
	public function isValid(string $token): bool
	{
		$expected = (string) $this->store->get($this->sessionKey, '');
		if ($expected === '') {
			return false;
		}

		return hash_equals($expected, $token);
	}

	/**
	 * Valide un token de requete POST (accepte les autres methodes).
	 */
	public function validateRequest(Request $request): bool
	{
		if (!$request->isPost()) {
			return true;
		}

		$token = $request->input($this->inputName, '');
		if (!is_string($token) || $token === '') {
			return false;
		}

		return $this->isValid($token);
	}

	/**
	 * Champ HTML a injecter dans un formulaire.
	 */
	public function field(): string
	{
		$token = $this->token();
		$name = htmlspecialchars($this->inputName, ENT_QUOTES, 'UTF-8');
		$value = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');

		return '<input type="hidden" name="' . $name . '" value="' . $value . '">';
	}

	private function generateToken(): string
	{
		return bin2hex(random_bytes(32));
	}
}