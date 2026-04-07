# 🏠 TaskBoard – Gestion de Colocation

Application web développée avec Laravel permettant de gérer efficacement des colocations, suivre les dépenses partagées et automatiser le calcul des dettes entre membres.

---

## 🎯 Objectif du projet

Ce projet vise à :

- Gérer des colocations (création, invitation, gestion des membres)
- Suivre les dépenses communes
- Calculer automatiquement les soldes entre membres
- Offrir une vue simplifiée des remboursements
- Mettre en place un système de rôles et de réputation

---

## 🚀 Fonctionnalités principales

### 👤 Utilisateurs
- Inscription / Connexion
- Gestion du profil
- Premier utilisateur promu automatiquement Admin global
- Blocage des utilisateurs bannis

### 🏠 Colocations
- Création de colocation (Owner automatique)
- Invitation via lien sécurisé (token + email)
- Une seule colocation active par utilisateur
- Départ d’un membre
- Annulation d’une colocation

### 💸 Dépenses
- Ajout de dépenses (montant, date, catégorie, payeur)
- Historique des dépenses
- Filtrage des dépenses par mois
- Statistiques par catégorie

### ⚖️ Balances & Dettes
- Calcul automatique des soldes
- Vue simplifiée : “qui doit à qui”
- Réduction des dettes avec "Marquer payé"

### ⭐ Réputation
- +1 : départ sans dette
- -1 : départ avec dette
- Gestion spéciale :
  - Si un owner retire un membre endetté → dette transférée à l’owner

### 🛠️ Administration (Global Admin)
- Dashboard global
- Statistiques (utilisateurs, colocations, dépenses)
- Bannissement / débannissement

---

## 🧱 Architecture & Technologies

- **Framework** : Laravel (MVC)
- **Backend** : PHP 8
- **Base de données** : MySQL / PostgreSQL
- **ORM** : Eloquent (hasMany, belongsToMany)
- **Authentification** : Laravel Breeze / Jetstream
- **Frontend** : Blade + Tailwind CSS
- **API** : RESTful

---

## 📊 Rôles

- **Member** : utilisateur standard dans une colocation  
- **Owner** : créateur et gestionnaire de la colocation  
- **Global Admin** : gestion globale de la plateforme  

---

## 📸 Aperçu

> Ajouter ici des captures d’écran (Dashboard, dépenses, balances…)

---

## ⚙️ Installation

```bash
git clone https://github.com/OUTERGA-MOUSTAFA/TaskBoard.git
cd TaskBoard

composer install
cp .env.example .env
php artisan key:generate

# configurer DB dans .env

php artisan migrate
php artisan serve
