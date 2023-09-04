# ToDo&Co - Lignes directrices de contribution

Si vous souhaitez contribuer à améliorer ce projet, votre contribution est la bienvenue !
Veuillez suivre les directives suivantes.

## Pré requis

Veuillez installer le projet en local en suivant les instructions présente dans le fichier readme [README.md](README.md).

## A propos de Symfony

Ce projet est développé avec Symfony Framework. Veuillez vous référer à la documentation [Symfony documentation](https://symfony.com/doc/current/index.html) suivre les meilleures pratiques de développement.

## Qualité du code

* Veuillez vous assurer de maintenir un niveau de qualité de catégorie A avec Codacy
* Veuillez également suivre les bonnes pratiques suivantes pour être sûr de respecter les exigences minimales du PSR ([PSR-1](https://www.php-fig.org/psr/psr-1/), [PSR-12](https://www.php-fig.org/psr/psr-12/) and [PSR-4](https://www.php-fig.org/psr/psr-4)): 

vous pouvez executer la commande suivante pour 

`vendor/bin/php-cs-fixer fix --diff --dry-run`


## Tests

Les tests unitaires et fonctionnels sont implémentés avec PHPunit.

#### Intégration continue

* Chaque fois que vous envoyez un commit ou une Pull Request, SymfonyInsight testera automatiquement votre code.
* S'il vou splait ne modifiez pas le fichier `.env.test`. Les instructions sur la configuration de la base de données de test pour les tests locaux sont décrites dans la section suivante.
* La couverture du code est >70 %. Veuillez vous assurer de ce niveau de couverture de test élevé.

#### PHPUnit en local

* Notez que vous ne pourrez pas fusionner un PR si l'analyse SymfonyInsight a échouée, nous vous recommandons donc fortement de tester votre code en local avant de le transférer dans le référentiel
* Pour lancer des tests sur votre machine locale, vous avez besoin d'une base de données de tests. Comme mentionné ci-dessus, veuillez ne pas modifier le fichier `.env.test`.
* Créer un fichier `.env.test.local` dans lequel vous allez pouvoir configurer votre variable d'environnement DATABASE_URL avec vos informations d'identification de base de données locales.
* Créez votre environnement de base de données de test en exécutant :

```console
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:update --force --env=test
```

* Pour lancer tous les tests:
```console
php bin/phpunit
```

* Pour lancer un test en particulier:
```console
php bin/phpunit --filter SpecificTest
```

## Instructions

* La branche de déploiement du projet est "master", veuillez donc ne jamais vous engager dessus. Veuillez plutôt vous engager sur une branche nouvellement créée.
* Après l'installation du projet sur votre machine locale, créez une nouvelle branche suivant la nomenclature :
    - 'fix/': pour les modifications 
    - 'feature/': pour une nouvelle fonctionnalité
* Travaillez sur votre propre branche. N'oubliez pas d'implémenter vos propres tests pour tester votre code.
* Vérifiez la qualité du code et vérifiez que votre code a réussi tous les tests.
* Si les analyses PhpUnit ou Symfonyinsight échouent, veuillez corriger vos bugs, puis valider et pousser.

