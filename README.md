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
5. **Configuration de Lexik JWT**

    Générez une paire de clés publique/privée pour Lexik JWT en utilisant la commande suivante :

    ```bash
    mkdir -p config/jwt
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    ```

    Lorsque vous exécutez ces commandes, on vous demandera de saisir une passphrase. Assurez-vous de conserver cette passphrase en sécurité, car elle sera nécessaire pour utiliser les clés générées.

    Après avoir généré les clés, configurez les chemins vers ces clés dans votre fichier `.env.local` :

    ```bash
    JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
    JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
    JWT_PASSPHRASE=password (modifer avec votre mot de passe)
    ```

    Si des problèmes persistent lors de l'installation n'oubliez pas de consulter la documentation de [Lexik JWT](https://symfony.com/bundles/LexikJWTAuthenticationBundle/current/index.html)
6. **Chargement des fixtures**

    Pour charger les données de test dans la base de données, exécutez :

    ```bash
    php bin/console doctrine:fixtures:load
    ```

7. **Démarrer le serveur local**

    Vous pouvez maintenant démarrer le serveur Symfony en exécutant la commande suivante :

    ```bash
    symfony serve
    ```

    Le serveur devrait démarrer sur `http://localhost:8000` par défaut.

8. **Utilisation de l'API**

    Vous pouvez maintenant utiliser l'API de BileMo en accédant à l'URL de base `http://localhost:8000/api`.

## Documentation

La documentation de l'API est disponible à l'adresse suivante en local:

[Documentation de l'API](http://localhost:8000/api/doc)
