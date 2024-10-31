# TI4sym64

## Structure des fichiers
# Dockerfile
- Le Dockerfile est le point de départ pour la configuration Docker de l’application Symfony.

***Base Image*** : Utilisez une image de base Docker appropriée, comme PHP avec FPM (FastCGI Process Manager), adaptée pour une application Symfony.
### Gestion des Dépendances :
Utilisez Composer pour installer les dépendances Symfony.
Veillez à structurer votre fichier Docker afin que les dépendances ne soient pas réinstallées à chaque modification mineure (utilisation du cache Docker).
### Optimisation du Dockerfile :
Segmentez les étapes dans le Dockerfile pour un cache optimal.
Assurez-vous de ne pas inclure d'outils ou de packages non nécessaires à l'exécution de l'application en production.
# docker-compose.yml
Le fichier docker-compose.yml est essentiel pour configurer et orchestrer les différents services nécessaires à l'application.

## Services

***App (Symfony)*** : Service pour l'application Symfony basé sur le Dockerfile défini ci-dessus.

***DB*** : Utilisez une base de données comme MySQL ou PostgreSQL. Configurez les paramètres de connexion pour que l'application Symfony puisse interagir avec elle.

***Nginx*** : Servira de serveur web pour distribuer l’application et gérer les requêtes.

## Volumes 
* Assurez la synchronisation des fichiers source Symfony entre le conteneur et l’hôte, pour un développement fluide.
* Configurez un volume pour la base de données afin de persister les données même si le conteneur est supprimé.

# Fonctionnalités de l’Application
* Démarrage et accessibilité : L'application Symfony doit pouvoir démarrer sans erreurs et être accessible via un navigateur à une adresse prédéfinie (ex. : http://localhost).
* Interaction avec la base de données : L'application doit pouvoir se connecter à la base de données et effectuer des opérations de lecture et d'écriture.
# Bonnes pratiques Docker
* Image Docker légère : Évitez de surcharger l'image Docker d'outils ou d'éléments non indispensables.
* Optimisation des Builds : Structurez les étapes du build pour éviter la réinstallation des paquets inutiles. Utilisez des instructions COPY et RUN optimisées pour maximiser le cache Docker.
* Configuration Nginx : Adaptez les configurations de Nginx pour de meilleures performances en production (caching, compression, sécurité de base).

# Évaluation
Votre travail sera évalué sur les points suivants :

* Structure et Clarté de votre Dockerfile et docker-compose.yml.
* Fonctionnalité : L'application démarre correctement, et est accessible via le navigateur et interagit avec la base de données.
* Optimisation : Votre build Docker est bien structuré, avec un Dockerfile optimisé et des services bien configurés.
* Respect des bonnes pratiques Docker : Votre Dockerisation est optimisée pour la production, avec une attention particulière portée aux performances et à la sécurité.

* NB: Vous trouverez à la racine du projet un dossier docker et à l'interieur je vous ai mis le fichier de configuration nginx en ``default.conf``

## Voici le fichier commenté 
 ```bash 
 server {
    # Le nom de domaine utilisé pour le serveur. Le serveur répondra à domain.tld et www.domain.tld
    server_name domain.tld www.domain.tld;
    
    # Définit le dossier racine où Nginx va chercher les fichiers. 
    # Symfony utilise généralement un dossier 'public' comme dossier web accessible.
    root /usr/src/app/public;  # Symfony utilise habituellement un dossier 'public'

    # Configuration de la localisation de la racine du site
    location / {
        # Nginx essaie d'abord de servir le fichier correspondant à l'URI demandée. 
        # Si le fichier n'existe pas, il redirige vers index.php (Symfony gère toutes les requêtes via index.php)
        try_files $uri /index.php$is_args$args;
    }

    # Gestion des requêtes vers index.php
    location ~ ^/index\.php(/|$) {
        # Spécifie le service PHP utilisé par Nginx pour traiter les requêtes PHP, 
        # ici défini comme le service PHP sur le port 9000 (lié à docker-compose.yml).
        fastcgi_pass php:9000;  # Correspond au service PHP dans docker-compose.yml
        
        # Sépare le chemin de la requête pour les fichiers PHP et les paramètres supplémentaires après le fichier PHP
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        
        # Inclut les paramètres FastCGI par défaut (nécessaires pour exécuter des scripts PHP)
        include fastcgi_params;

        # Spécifie le fichier script à exécuter. Ici, Nginx combine la racine du document 
        # et le nom du script pour déterminer le chemin complet.
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

        # Définit la racine du document (le répertoire public de Symfony)
        fastcgi_param DOCUMENT_ROOT $document_root;

        # Directive interne : cela signifie que cet emplacement ne peut pas être appelé directement par un client.
        internal;
    }

    # Gestion de tous les autres fichiers PHP
    location ~ \.php$ {
        # Spécifie à nouveau le service PHP, utilisé pour traiter toutes les requêtes .php
        fastcgi_pass php:9000;

        # Indique le fichier index à appeler si une requête PHP n'a pas de fichier spécifique
        fastcgi_index index.php;

        # Détermine le chemin complet du fichier PHP à exécuter
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

        # Inclut les paramètres FastCGI nécessaires pour exécuter les scripts PHP
        include fastcgi_params;
    }

    # Fichier pour enregistrer les logs d'erreurs Nginx (liés à ce serveur)
    error_log /var/log/nginx/project_error.log;

    # Fichier pour enregistrer les logs des accès au serveur Nginx
    access_log /var/log/nginx/project_access.log;
}
```

Vous aurez à créer un ``Dockerfile`` et configurer le ``compose.yaml`` pour dockériser votre projet 
## Dockerfile
* Concernant le ``Dockerfile`` voici le fichier à compléter.
```bash
# Utiliser l'image de base PHP 8.1 avec fpm (FastCGI Process Manager) pour Alpine Linux 3.16
?????? 
# installer les outils de base sans cache pour réduire l'image finale, avec des versions spécifiques :
??????

# Définicer le répertoire de travail par défaut sur /usr/src/app.
??????

# Copier les fichiers de configuration de Composer pour gérer les dépendances PHP.
??????

# Ajoute les répertoires bin et vendor/bin au PATH, facilitant l'accès aux commandes installées par Composer.
RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

# Copier Composer depuis une image précédente pour éviter une réinstallation.
??????

# Copier tous les fichiers de l'application dans le répertoire de
??????

# Installer les dépendances PHP spécifiées dans composer.json.
??????
```
## compose.yaml
Concernant le fichier ``compose.yaml``, n'oubliez pas que Ce fichier configure une pile d'application web complète et nous aurons besoin de :

* ***MySQL*** pour la base de données,
* ***PhpMyAdmin*** pour la gestion de la base de données,
* ***Nginx*** comme serveur web, et
* ***PHP*** pour exécuter le code de l'application.
* ***Les volumes*** et ***réseaux*** assurent la persistance des données et la communication interne entre les services.

## En résumé
Pour ce travail pratique, je m'attends à ce que vous obteniez un effet miroir :
ce que vous avez en local doit être identique sur le serveur Nginx du projet dockerisé.
Il n’est donc pas nécessaire de publier l’image sur Docker Hub.
Je ne vous demande pas d’effectuer des étapes que nous n’avons pas abordées en classe. 
Vous devrez envoyer votre projet sur GitHub avec un effet miroir fonctionnel, de sorte qu’il puisse également fonctionner correctement sur mon ordinateur.

***NB***: N'oubliez pas les bonus pour ceux qui vont vite ...

Bon boulot !