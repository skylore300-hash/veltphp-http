# Issue 01 - Creer Request et Response

## Labels

`module:1-foundations`, `area:http`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Creer les objets HTTP de base utilises par le routeur et les controllers Velt.

## Travail attendu

- Creer `Velt\Http\Request`.
- Creer `Velt\Http\Response`.
- Creer `Velt\Http\JsonResponse`.
- Ajouter des factories depuis les superglobales PHP.
- Gerer headers, status code et body.

## API minimale attendue

```php
$request = Request::capture();
$request->method();
$request->path();
$request->query('page', 1);
$request->input('email');

return Response::html('<h1>Hello</h1>', 200);
return Response::json(['ok' => true], 200);
```

## Contraintes

- Ne pas dependre d'un framework HTTP externe pour le MVP.
- Rester compatible avec le serveur PHP local.
- Preparer une evolution future vers PSR-7 sans l'imposer maintenant.

## Criteres d'acceptation

- Une requete peut etre construite depuis `$_SERVER`, `$_GET`, `$_POST`.
- Une response HTML peut etre envoyee.
- Une response JSON definit le header `Content-Type: application/json`.
- Les tests couvrent method, path, input, query, status et headers.

## Definition of Done

- Classes creees.
- Tests unitaires verts.
- README mis a jour avec exemples.

## Issue 01 - Request et Response

### Objectif
Poser la base HTTP minimale de Velt: capturer une requête et envoyer une réponse.

### Réalisation
- `Request` encapsule les superglobales PHP (`$_SERVER`, `$_GET`, `$_POST`) et expose `method()`, `path()`, `query()`, `input()`, `header()`.
- `Response` construit une réponse HTML avec status, headers et body.
- `JsonResponse` encode automatiquement les données et ajoute `Content-Type: application/json`.

### Pourquoi c’est important
- Base commune pour le routeur et les controllers
- Testable sans dépendre directement de PHP natif
- Facile à faire évoluer vers d’autres types de réponses

### Tests
- `RequestTest`: méthode, path, query, input, helpers
- `ResponseTest`: HTML, JSON, headers, status