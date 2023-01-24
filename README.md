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

<!-- Le projet a été déployé sur un VPS avec les versions suivantes :
>- Apache 2.4.52
>- PHP 7.4.26
>- [MySQL](https://openclassrooms.com/fr/courses/918836-concevez-votre-site-web-avec-php-et-mysql/913893-mettez-en-place-une-base-de-donnees-avec-phpmyadmin) 5.1.1
>- [Composer](https://getcomposer.org/) 2.3.7 -->

## Base de données

- Par défaut, l'application utilise une base de données MySQL dénommée `blog`, accessible à un utilisateur `root` dont le mot de passe est `password`. 
- Vous pouvez modifier ces paramètres dans le fichier `.env` situé à la racine `/` du projet.
``` php
DB_HOST // adresse du serveur MySQL
DB_NAME // nom de la base de données
DB_USER // nom d'utilisateur
DB_PASS // mot de passe
```

## Installation

- Cloner le dépôt Git
``` bash
git clone https://github.com/jeremymls/blog.git
```

- Installer Composer
``` bash
curl -sS https://getcomposer.org/installer | php
```

- Lancer deploy.sh
``` bash
./deploy.sh
```

## Configuration

Une fois installé, vous pouvez accéder à l'application via votre navigateur web.
Vous pouvez vous connecter et accéder à l'administration avec les identifiants suivants :
``` bash
Identifiant :  admin
Mot de passe : jmp2022
```