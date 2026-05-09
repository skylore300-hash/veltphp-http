# Sous-module 02 - HTTP Routing

## Mission

Ce sous-module fournit la couche HTTP minimale de Velt : requetes, reponses, routeur, dispatch et middleware simple.

Il doit permettre au MVP de servir une page Web, une response JSON et les endpoints de preview.

Apres audit, HTTP doit aussi poser des contrats solides pour que le framework puisse evoluer vers redirect, stream, file download, erreurs JSON/HTML et middleware propre. Le Module 1 n'implemente pas tout, mais il doit definir les points d'extension maintenant.

## Perimetre

Inclus :

- `Request`
- `Response`
- `JsonResponse`
- `ResponseInterface`
- `ResponsableInterface` ou normalizer equivalent ;
- `Router`
- routes GET/POST ;
- parametres dynamiques comme `/api/preview/{id}` ;
- pipeline middleware explicite ;
- session minimale pour CSRF si `Form::csrf()` est expose par UI ;
- erreurs HTTP propres en HTML ou JSON.

Exclus :

- ORM ;
- moteur UI ;
- serveur Web avance ;
- authentification complete.

## Comment tester sans UI, Preview ou Database

HTTP doit se tester avec des handlers fake.

- Pour tester une page UI sans `veltphp/ui`, creer une classe fake qui implemente `RenderableInterface` et retourne une string HTML.
- Pour tester un retour JSON, utiliser un tableau PHP simple et verifier le header `Content-Type`.
- Pour tester le pipeline, creer deux middlewares fake qui ajoutent un header ou bloquent la requete.
- Pour tester les erreurs, forcer une route absente, une methode non autorisee et une exception controller.
- Pour tester CSRF sans module session complet, utiliser un `SessionStoreInterface` fake en memoire.

Le module HTTP ne doit pas importer les composants UI reels dans ses tests unitaires. L'integration avec UI se fait dans les tests du sous-module 07.

## Issues

- [Issue 01 - Creer Request et Response](issues/01-creer-request-response.md)
- [Issue 02 - Implementer le routeur MVP](issues/02-implementer-routeur-mvp.md)
- [Issue 03 - Ajouter middleware et dispatch controller](issues/03-ajouter-middleware-dispatch-controller.md)
- [Issue 04 - Normaliser les retours controller et erreurs HTTP](issues/04-normaliser-retours-controller-erreurs-http.md)
- [Issue 05 - Definir ResponseInterface et types de response futurs](issues/05-definir-responseinterface-types-response.md)
- [Issue 06 - Implementer Pipeline middleware explicite](issues/06-implementer-pipeline-middleware-explicite.md)
- [Issue 07 - Ajouter session et CSRF minimal](issues/07-session-csrf-minimal.md)
