<h1>BileMo</h1>

<p>BileMo est un projet Symfony 6 conçu pour fournir une API pour une entreprise fictive vendant des téléphones mobiles.</p>

<h2>Installation</h2>

<h3>Prérequis</h3>

<p>Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre système :</p>
<ul>
    <li>PHP 8.1 ou supérieur</li>
    <li>Composer (<a href="https://getcomposer.org/">https://getcomposer.org/</a>)</li>
    <li>Symfony CLI (<a href="https://symfony.com/download">https://symfony.com/download</a>)</li>
    <li>MySQL ou tout autre système de gestion de base de données pris en charge par Symfony</li>
</ul>

<h3>Étapes d'installation</h3>

<ol>
    <li><strong>Cloner le dépôt</strong></li>

    <p><code>git clone https://github.com/Galypso-Unreal/BileMo.git</code></p>

    <li><strong>Installer les dépendances</strong></li>

    <p>Accédez au répertoire du projet et exécutez la commande suivante pour installer les dépendances :</p>
    <pre><code>cd BileMo

composer install
</code></pre>

    <li><strong>Configuration de la base de données</strong></li>

    <p>Dupliquez le fichier <code>.env</code> et nommez-le <code>.env.local</code>. Modifiez ce fichier pour configurer votre base de données :</p>
    <pre><code>DATABASE_URL=mysql://user:password@localhost:3306/bilemo

</code></pre>

<p>Remplacez <code>user</code> et <code>password</code> par votre nom d'utilisateur et mot de passe de base de données respectivement, et <code>bilemo</code> par le nom de votre base de données.</p>

    <li><strong>Création de la base de données</strong></li>

    <p>Exécutez les commandes suivantes pour créer la base de données et les schémas associés :</p>
    <pre><code>php bin/console doctrine:database:create

php bin/console doctrine:schema:create
</code></pre>

    <li><strong>Chargement des fixtures</strong></li>

    <p>Pour charger les données de test dans la base de données, exécutez :</p>
    <pre><code>php bin/console doctrine:fixtures:load

</code></pre>

    <li><strong>Démarrer le serveur local</strong></li>

    <p>Vous pouvez maintenant démarrer le serveur Symfony en exécutant la commande suivante :</p>
    <pre><code>symfony serve

</code></pre>

<p>Le serveur devrait démarrer sur <code>http://localhost:8000</code> par défaut.</p>

    <li><strong>Utilisation de l'API</strong></li>

    <p>Vous pouvez maintenant utiliser l'API de BileMo en accédant à l'URL de base <code>http://localhost:8000/api</code>.</p>

</ol>

<h2>Documentation</h2>

<p>La documentation de l'API est disponible à l'adresse suivante en local:</p>
<p><a href="http://localhost:8000/api/doc">http://localhost:8000/api/doc</a></p>
