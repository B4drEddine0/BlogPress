# BlogPress

## 📝 Aperçu du Projet

BlogPress est une plateforme de blog moderne développée en PHP et MySQL, offrant aux utilisateurs la possibilité de publier des articles et de suivre leur performance grâce à des fonctionnalités d'analyse intégrées. Ce projet a été réalisé dans le cadre d'un exercice de développement PHP junior.

🌐 [Lien du Dépôt GitHub](https://github.com/B4drEddine0/BlogPress)

## 🚀 Fonctionnalités Principales

### Système d'Authentification
- Inscription des utilisateurs
- Connexion/Déconnexion
- Gestion des rôles (auteur/visiteur)
- Protection des routes sensibles

### Page d'Accueil
- Liste des articles triés par popularité
- Affichage du nombre de vues et commentaires
- Visualisation des données avec Chart.js (graphique des articles les plus populaires)

### Page d'Article
- Système de commentaires
- Compteur de vues
- Bouton "like" interactif
- Indicateur de temps de lecture

### Dashboard Auteur
- Statistiques des articles (vues, commentaires, likes)
- Gestion des articles (CRUD)
- Visualisation des données avec Chart.js (évolution des vues dans le temps)

## 🛠️ Technologies Utilisées

- PHP 7.4+
- MySQL 5.7+
- HTML5, CSS3, JavaScript
- Chart.js pour la visualisation des données

## 👥 User Stories

### Visiteur
- Voir les articles les plus populaires
- Commenter les articles
- Voir les statistiques basiques d'un article

### Auteur
- Créer, modifier et supprimer ses articles
- Voir les statistiques de ses articles
- Gérer les commentaires sur ses articles
- Visualiser la performance des articles via des graphiques

## 🗄️ Structure du Projet
BlogPress/
│
├── config/
│   └── database.php
├── controllers/
│   ├── AuthController.php
│   ├── ArticleController.php
│   └── CommentController.php
├── models/
│   ├── User.php
│   ├── Article.php
│   └── Comment.php
├── views/
│   ├── auth/
│   ├── articles/
│   └── dashboard/
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── utils/
│   └── helpers.php
└── index.php

## 🔧 Installation et Configuration

1. Clonez le dépôt :
git clone [https://github.com/B4drEddine0/BlogPress.git](https://github.com/B4drEddine0/BlogPress.git)
2. Importez la structure de la base de données depuis `database.sql`
3. Configurez les paramètres de connexion à la base de données dans `config/database.php`
4. Assurez-vous que votre serveur web pointe vers le dossier `public` du projet

## 📈 Fonctionnalités Bonus (À Implémenter)

- Barre de recherche dynamique (using API)
- Filtrage des statistiques par période
- Système de catégories et tags
- Export des statistiques en CSV

## 🤝 Contribution

Les contributions sont les bienvenues ! Veuillez suivre ces étapes :

1. Forkez le projet
2. Créez votre branche de fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Poussez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📞 Support

Pour toute question ou problème, veuillez ouvrir un ticket dans la section Issues du dépôt GitHub.

## 📄 Licence

Ce projet est réalisé dans le cadre d'un exercice éducatif et n'est pas sous licence spécifique.

---

Développé par Badr Eddine Massa Al Khayr dans le cadre d'un projet de développement PHP junior.
