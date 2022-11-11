# OpenClassrooms - Projet 5 - Créez votre premier blog en PHP

## Présentation

Voici le dépôt Git de [JM Projets](http://jm-projets.fr), mon portfolio de projets.  
Ce projet est le cinquième projet de la formation Développeur d'application - PHP/Symfony d'OpenClassrooms.  
Il s'agit de créer un blog en PHP, sans framework, en utilisant une base de données MySQL.  
Il est personnalisable, et il est possible de créer des articles, des commentaires, des utilisateurs, etc.

## Configuration conseillée

Le projet a été développé sur un serveur local avec les versions suivantes :
>- Apache 2.4.52
>- PHP 7.4.26
>- [MySQL](https://openclassrooms.com/fr/courses/918836-concevez-votre-site-web-avec-php-et-mysql/913893-mettez-en-place-une-base-de-donnees-avec-phpmyadmin) 5.1.1
>- [Composer](https://getcomposer.org/) 2.3.7

## Base de données

- Par défaut, l'application utilise une base de données MySQL dénommée `blog`, accessible à un utilisateur `root` dont le mot de passe est `password`. Vous pouvez modifier ces paramètres dans le fichier `.env` situé à la racine `/` du projet.
``` php
DB_HOST // adresse du serveur MySQL
DB_NAME // nom de la base de données
DB_USER // nom d'utilisateur
DB_PASS // mot de passe
```

- Il est possible de créer la base de données à partir du fichier `blog.sql` présent dans le dossier `docs/sql`.
```bash
mysql -uroot -p password < sql/blog.sql
```
*:warning: ATTENTION :warning:*
*Si vous avez modifié database.php, pensez à remplacer `root` et `password` par vos identifiants*

## Installation

- Cloner le dépôt Git
``` bash
git clone https://github.com/jeremymls/blog.git
```

- Installer Composer
``` bash
curl -sS https://getcomposer.org/installer | php
```

- Installer les dépendances
``` bash
php composer.phar install
```
- Copier le fichier `.env.example` et le renommer en `.env`
``` bash
cp .env.example .env
```
- Configurer le fichier `.env` avec vos identifiants de base de données
``` php
SITE_URL // URL du site

DB_HOST // adresse du serveur MySQL
DB_NAME // nom de la base de données
DB_USER // nom d'utilisateur
DB_PASS // mot de passe
```

## Lancement du serveur

* Vous pouvez utiliser le serveur web intégré à PHP pour lancer ce projet. Placez vous dans le dossier `blog/`, puis lancez la commande `php -S localhost:8080` (vous pouvez choisir le port que vous souhaitez si `8080` est déjà utilisé).
* Alternativement, et si vous avez une _stack_ WAMP ou LAMP installée, vous pouvez configurer votre serveur Apache pour qu'il gère le dossier `blog/`.

*:warning: IMPORTANT :warning:*
*La racine du site doit être un **nom de domaine** ou une **adresse IP.***
Par exemple, si vous utilisez un serveur local, vous devez utiliser `localhost:8080` et non un chemin comme `localhost:8080/blog/`.
## Configuration

Une fois installé et lancé, vous pouvez accéder à l'application via votre navigateur web.  
Vous pouvez vous connecter à l'application avec les identifiants suivants :
``` bash
Identifiant :  admin
Mot de passe : jmp2022
```