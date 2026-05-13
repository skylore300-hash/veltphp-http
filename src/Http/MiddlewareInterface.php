<?php

namespace Velt\Http;

interface MiddlewareInterface
{
	/**
	 * Traite la requete et delegue au middleware suivant.
	 */
	public function handle(Request $request, callable $next): ResponseInterface;
}
