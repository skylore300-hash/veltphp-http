# Issue 04 - Normaliser les retours controller et erreurs HTTP

## Labels

`module:1-foundations`, `area:http`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Garantir que tous les retours de controllers deviennent des responses HTTP correctes.

## Pourquoi cette issue est obligatoire

Un controller Velt pourra retourner plusieurs types de valeurs : `Response`, tableau JSON, string HTML ou objet renderable. Sans normalisation, chaque route risque de gerer ses retours differemment.

## Travail attendu

- Creer un `ResponseFactory` ou `ResponseNormalizer`.
- Convertir une string en response HTML.
- Convertir un tableau en response JSON.
- Convertir un objet `RenderableInterface` en response HTML.
- Convertir un objet `JsonableInterface` en response JSON si le contexte API le demande.
- Ajouter une gestion propre des exceptions HTTP.

## Contraintes

- Ne pas coupler HTTP directement a une classe concrete de `veltphp/ui`.
- Passer par les contrats du kernel.
- Ne pas afficher les details d'exception en mode production.

## Criteres d'acceptation

- Un controller qui retourne `Response` fonctionne.
- Un controller qui retourne `array` produit du JSON.
- Un controller qui retourne `string` produit du HTML.
- Un objet renderable peut etre transforme en response.
- Une route inexistante retourne 404.
- Une methode non autorisee retourne 405.
- Une exception controller retourne une response 500 propre.

## Definition of Done

- Normalizer implemente.
- Tests unitaires et integration.
- Documentation avec exemples de retours controller.

