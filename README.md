
# Hackaton Prime - Gestion des Clients et Factures

Ce projet est une application Symfony permettant de gérer les clients et leurs factures. Il inclut des fonctionnalités comme la gestion des clients, l'ajout, l'édition et la suppression de factures, ainsi qu'un système de pagination et de recherche pour afficher et gérer les informations.

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- **PHP 8.0+**
- **Composer** : Le gestionnaire de dépendances PHP
- **Symfony CLI** (optionnel mais recommandé)

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/your-username/hackatonPrime.git
cd hackatonPrime

2. Installer les dépendances PHP avec Composer

composer install

3. Créer et configurer la base de données

php bin/console doctrine:database:create  # Crée la base de données
php bin/console doctrine:schema:update --force  # Crée les tables nécessaires


4. Lancer le serveur Symfony (si vous utilisez Symfony CLI)

symfony serve

Ou, si vous n'utilisez pas Symfony CLI, vous pouvez utiliser le serveur PHP intégré :

php -S 127.0.0.1:8000 -t public

5. Accéder à l'application

Une fois le serveur démarré, vous pouvez accéder à l'application dans votre navigateur en allant à http://127.0.0.1:8000.

Fonctionnalités

Gestion des clients :

Créer, modifier et supprimer des clients

Afficher les informations détaillées de chaque client

Recherche et pagination des clients


Gestion des factures :

Créer, modifier et supprimer des factures

Lier des factures à des clients

Calcul du montant total des factures pour chaque client


Pagination :

Pagination des clients et des factures pour éviter les longs tableaux.

Personnalisation du nombre d'éléments par page (10, 25, 50, 100).


Filtrage :

Recherche par nom de client.

Trier par nom, date, montant ou statut de la facture.


Structure du projet

/config              # Configuration du projet
/src                 # Code source
    /Controller      # Contrôleurs pour gérer la logique de chaque page
    /Entity          # Entités Doctrine pour la gestion des données
    /Form            # Formulaires Symfony pour l'ajout et la modification de données
    /Repository      # Repositories pour interagir avec la base de données
/templates           # Templates Twig pour l'affichage des pages
/public              # Fichiers publics (images, CSS, JS)
  /assets            # Assets pour le front-end
  /build             # Dossier de build pour le front-end
/tests               # Tests unitaires et fonctionnels


### Explication des sections :

- **Installation** : Guide pas à pas sur la façon de cloner, installer les dépendances et configurer la base de données.
- **Fonctionnalités** : Décrit les fonctionnalités principales du projet comme la gestion des clients et des factures, la pagination, et les filtres.
- **Structure du projet** : Donne une vue d'ensemble des répertoires du projet pour une meilleure organisation.

Vous pouvez personnaliser ce **README** en fonction de vos spécificités et l'adapter à la structure exacte de votre projet.

Voici un exemple de fichier README.md pour votre projet Symfony, structuré pour être utilisé dans un dépôt GitHub. Il décrit le projet, comment l'installer, le configurer et l'utiliser. Vous pouvez l'adapter selon vos besoins spécifiques.

