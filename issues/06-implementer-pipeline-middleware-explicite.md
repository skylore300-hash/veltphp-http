# Issue 06 - Implementer Pipeline middleware explicite

## Labels

`module:1-foundations`, `area:http`, `area:pipeline`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Remplacer la notion vague de middleware par un pipeline explicite de type onion architecture.

## Travail attendu

- Creer `Pipeline`.
- Definir `MiddlewareInterface`.
- Chaque middleware recoit la request et un `next`.
- Le route handler est l'etape finale du pipeline.
- Supporter middleware global et middleware de route si possible.
- Garder l'API simple.

## Criteres d'acceptation

- Deux middlewares s'executent dans l'ordre attendu.
- Un middleware peut court-circuiter avec une response.
- Le handler final est appele seulement si tous les middlewares appellent `next`.
- Les erreurs dans le pipeline sont remises a l'exception handler.

## Definition of Done

- Pipeline implemente.
- Tests d'ordre d'execution.
- Tests de blocage.
- Documentation avec schema simple.

