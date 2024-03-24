# BileMo

BileMo est un projet Symfony 6 conçu pour fournir une API pour une entreprise fictive vendant des téléphones mobiles.

## Installation

### Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre système :

- PHP 8.1 ou supérieur
- Composer (https://getcomposer.org/)
- Symfony CLI (https://symfony.com/download)
- MySQL ou tout autre système de gestion de base de données pris en charge par Symfony

### Étapes d'installation

1. **Cloner le dépôt**

    ```bash
    git clone https://github.com/Galypso-Unreal/BileMo.git
    ```

2. **Installer les dépendances**

    Accédez au répertoire du projet et exécutez la commande suivante pour installer les dépendances :

    ```bash
    cd BileMo
    composer install
    ```

3. **Configuration de la base de données**

    Dupliquez le fichier `.env` et nommez-le `.env.local`. Modifiez ce fichier pour configurer votre base de données :

    ```bash
    DATABASE_URL=mysql://user:password@localhost:3306/bilemo
    ```

    Remplacez `user` et `password` par votre nom d'utilisateur et mot de passe de base de données respectivement, et `bilemo` par le nom de votre base de données.

4. **Création de la base de données**

    Exécutez les commandes suivantes pour créer la base de données et les schémas associés :

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:create
    ```

5. **Chargement des fixtures**

    Pour charger les données de test dans la base de données, exécutez :

    ```bash
    php bin/console doctrine:fixtures:load
    ```

6. **Démarrer le serveur local**

    Vous pouvez maintenant démarrer le serveur Symfony en exécutant la commande suivante :

    ```bash
    symfony serve
    ```

    Le serveur devrait démarrer sur `http://localhost:8000` par défaut.

7. **Utilisation de l'API**

    Vous pouvez maintenant utiliser l'API de BileMo en accédant à l'URL de base `http://localhost:8000/api`.

## Documentation

La documentation de l'API est disponible à l'adresse suivante en local:

[Documentation de l'API](http://localhost:8000/api/doc)
