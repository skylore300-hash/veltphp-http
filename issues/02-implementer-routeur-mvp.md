# Issue 02 - Implementer le routeur MVP

## Labels

`module:1-foundations`, `area:http`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Creer un routeur simple capable de declarer des routes Web et API.

## API cible

```php
$router->get('/', [HomeController::class, 'index']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/api/preview/{id}', [PreviewController::class, 'show']);
```

## Fonctionnalites attendues

- Declaration de routes GET et POST.
- Matching de chemin exact.
- Matching de parametres dynamiques `{id}`.
- Retour 404 si aucune route ne correspond.
- Retour 405 si le chemin existe mais pas la methode.

## Contraintes

- Ne pas integrer de systeme de nommage de routes dans cette issue.
- Ne pas ajouter les groupes de routes dans le MVP de cette issue.
- Garder le matching lisible et testable.

## Criteres d'acceptation

- `/` matche une route statique.
- `/api/preview/demo123` matche `/api/preview/{id}` et extrait `demo123`.
- Le controller recoit les parametres de route.
- 404 et 405 sont geres proprement.

## Definition of Done

- Routeur implemente.
- Tests couvrant route statique, route dynamique, 404 et 405.
- Exemple documente.

