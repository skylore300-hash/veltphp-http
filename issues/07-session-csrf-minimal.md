# Issue 07 - Ajouter session et CSRF minimal

## Labels

`module:1-foundations`, `area:http`, `area:security`, `type:feature`, `priority:p1`, `status:ready`

## Objectif

Fournir la base minimale necessaire pour que `Form::csrf()` ait un sens sans attendre le module session complet.

## Travail attendu

- Creer `SessionStoreInterface` minimal.
- Ajouter une implementation fichier ou native PHP session tres simple.
- Creer `CsrfTokenManager`.
- Generer un token.
- Valider un token sur requete POST.
- Exposer une methode permettant au renderer HTML d'obtenir le champ cache.

## Contraintes

- Pas de session guard.
- Pas d'authentification.
- Pas de cookies avances.
- Le module session complet reste au Module 4.

## Criteres d'acceptation

- Un token CSRF peut etre genere.
- Une requete POST avec token valide passe.
- Une requete POST avec token absent ou invalide echoue proprement.
- UI peut marquer un formulaire comme `csrf` sans connaitre la session.

## Definition of Done

- Interfaces et implementation minimale crees.
- Tests generation/validation.
- Documentation de la limite Module 1.

