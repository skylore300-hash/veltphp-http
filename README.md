# Sous-module 02 - HTTP Routing

## Mission

Ce sous-module fournit la couche HTTP minimale de Velt : requetes, reponses, routeur, dispatch et middleware simple.

Il doit permettre au MVP de servir une page Web, une response JSON et les endpoints de preview.

## Perimetre

Inclus :

- `Request`
- `Response`
- `JsonResponse`
- `Router`
- routes GET/POST ;
- parametres dynamiques comme `/api/preview/{id}` ;
- middleware minimal.

Exclus :

- ORM ;
- moteur UI ;
- serveur Web avance ;
- authentification complete.

## Issues

- [Issue 01 - Creer Request et Response](issues/01-creer-request-response.md)
- [Issue 02 - Implementer le routeur MVP](issues/02-implementer-routeur-mvp.md)
- [Issue 03 - Ajouter middleware et dispatch controller](issues/03-ajouter-middleware-dispatch-controller.md)

