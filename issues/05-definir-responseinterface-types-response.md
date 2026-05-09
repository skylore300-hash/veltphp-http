# Issue 05 - Definir ResponseInterface et types de response futurs

## Labels

`module:1-foundations`, `area:http`, `type:architecture`, `type:feature`, `priority:p0`, `status:ready`

## Objectif

Definir un contrat commun pour toutes les responses HTTP afin de ne pas enfermer Velt dans HTML et JSON uniquement.

## Travail attendu

- Creer `ResponseInterface`.
- Exposer status code, headers, body et `send()`.
- Garder `Response` HTML et `JsonResponse` comme implementations MVP.
- Documenter les evolutions futures : `RedirectResponse`, `StreamResponse`, `FileResponse`, `BinaryResponse`, SSE.
- Preparer une compatibilite future PSR-7 sans l'imposer immediatement.

## Criteres d'acceptation

- `Response` et `JsonResponse` implementent le meme contrat.
- Un controller peut retourner une `ResponseInterface`.
- Les headers sont normalises.
- Les tests couvrent status, body et content-type.

## Definition of Done

- Contrat cree.
- Responses existantes alignees.
- README HTTP mis a jour.

