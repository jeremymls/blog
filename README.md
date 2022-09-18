# OpenClassrooms - Projet 5 - Créez votre premier blog en PHP

## Présentation

Voici le dépôt Git de [JM Projets](http://jm-projets.fr), mon portfolio de projets.  
Ce projet est le cinquième projet de la formation Développeur d'application - PHP/Symfony d'OpenClassrooms.  
Il s'agit de créer un blog en PHP, sans framework, en utilisant une base de données MySQL.  
Il est personnalisable, et il est possible de créer des articles, des commentaires, des utilisateurs, etc.

## Prérequis

- PHP 7.4 ou supérieur
- [MySQL](https://openclassrooms.com/fr/courses/918836-concevez-votre-site-web-avec-php-et-mysql/913893-mettez-en-place-une-base-de-donnees-avec-phpmyadmin) 5.7 ou supérieur
- [Composer](https://getcomposer.org/) 2.0 ou supérieur

## Base de données

- Par défaut, l'application utilise une base de données MySQL dénommée `blog`, accessible à un utilisateur `root` dont le mot de passe est `password`. Vous pouvez modifier ces paramètres dans le fichier `database.php` situé dans le dossier `blog/src/config/`.
``` php
    DB_HOST // adresse du serveur MySQL
    DB_NAME // nom de la base de données
    DB_USER // nom d'utilisateur
    DB_PASS // mot de passe
```

- Il est possible de créer la base de données à partir du fichier `blog.sql` présent dans le dossier `sql`.
```bash
    mysql -uroot -p password < sql/blog.sql
```
*:warning: Si vous avez modifié database.php, pensez à remplacer `root` et `password` par vos identifiants :warning:*

## Installation

- Cloner le dépôt Git
``` bash
    git clone https://github.com/jeremymls/blog.git
```

- Installer les dépendances avec Composer
``` bash
    composer install
```

## Lancement

Vous pouvez utiliser le serveur web intégré à PHP pour lancer ce projet. Placez vous dans le dossier `blog/`, puis lancez la commande `php -S localhost:8080` (vous pouvez choisir le port que vous souhaitez si `8080` est déjà utilisé).

Alternativement, et si vous avez une _stack_ WAMP ou LAMP installée, vous pouvez configurer votre serveur Apache pour qu'il gère le dossier `blog/`.

## Configuration

Une fois installé et lancé, vous pouvez accéder à l'application via votre navigateur web.  
Vous pouvez vous connecter à l'application avec les identifiants suivants :
``` bash
    Identifiant :  admin
    Mot de passe : password
```