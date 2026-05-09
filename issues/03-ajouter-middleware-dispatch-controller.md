# Issue 03 - Ajouter middleware et dispatch controller

## Labels

`module:1-foundations`, `area:http`, `type:feature`, `priority:p1`, `status:ready`

## Objectif

Permettre au routeur d'executer des controllers et une chaine middleware simple.

## Travail attendu

- Supporter les handlers callable.
- Supporter les handlers `[ClassName::class, 'method']`.
- Resoudre les controllers via le container si disponible.
- Ajouter un contrat `MiddlewareInterface`.
- Permettre d'attacher un middleware a une route.

## API cible

```php
$router->get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(AuthMiddleware::class);
```

## Contraintes

- Garder le middleware simple dans cette issue ; le pipeline explicite complet est traite par l'issue 06.
- Pas de guards/auth complete ici.
- Le middleware doit recevoir la request et un next callable.

## Criteres d'acceptation

- Un controller peut retourner `Response`.
- Un controller peut retourner un tableau qui devient JSON si la route API le demande.
- Un middleware peut bloquer la requete avec une response.
- Un middleware peut laisser passer la requete.

## Definition of Done

- Dispatch controller fonctionnel.
- Middleware minimal fonctionnel.
- Tests unitaires et integration simple.
