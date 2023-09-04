## Description

ToDo & Co est une nouvelle startup développant une application pour gérer les tâches de la vie quotidienne. Cependant, une application a été développée très rapidement pour faire la démonstration aux investisseurs potentiels, en tant que produit minimum viable. Ce projet vise à implémenter de nouvelles fonctionnalités, corriger quelques anomalies et améliorer la qualité des applications. Parmi eux : - Améliorer les performances et la qualité des applications obsolètes - Implémenter la relation entre la tâche et l'utilisateur - Ajouter la gestion des rôles des utilisateurs - Implémenter des restrictions d'autorisations - Implémenter des tests unitaires et fonctionnels pour obtenir une couverture des tests > 70% - Générer un audit de qualité et de performance après l'application amélioration - Suggérer un plan d'amélioration pour un développement ultérieurt

## Environment : Symfony 5 project

Requierement:
* [Composer]("https://getcomposer.org/")
* PHP 7.4

## Installation

#### 1 - Cloner le projet Git
`https://github.com/NicoRiso13/Projet8_TodoList.git`

#### 2 - Installer les librairies
`php bin/console composer install`

#### 3 - Créer la base de données
* a) Update DATABASE_URL .env file with your database configuration.
    `DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name`
* b) Create database: 
    `php bin/console doctrine:database:create`
* c) Create database structure:
    `php bin/console doctrine:schema:update --force`
* d) Insert fictive data
    `php bin/console doctrine:fixtures:load --group=UserFixtures --group=TaskFixtures`

#### 4 - démarrer le serveur
`symfony serve -d`

#### 5 - Ouvrir l'application ToDoList
`symfony open:local`

## Utilisation

* User: username: "user1" - password: "password"
* Administrator: Nom d'utilisateur: "admin1" - Mot de passe: "password"

## Testing

Lancer un test spécifique:
`php bin/phpunit --filter TaskControllerTest`

Générer une mise à jour des tests:
`php bin/phpunit --coverage-html public/test-coverage`
le resultat est accessible sur /public/test-coverage/index.html


## Documentation

* Guide d'authentification: /documentation
* Qualité et Performance: /documentation
