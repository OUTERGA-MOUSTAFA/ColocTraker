<h1 align="center">🏠 ColocTraker</h1>

<p align="center">
  <strong>Gérez les dépenses partagées de votre colocation, simplement.</strong><br>
  Suivi des dépenses · Calcul automatique des soldes · Remboursements simplifiés · Système de réputation
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3-38B2AC?logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?logo=alpinedotjs&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Tests-Pest-5E2D7D" alt="Pest">
</p>

<p align="center">
  🇫🇷 <strong>Français</strong> &nbsp;|&nbsp; <a href="README.en.md">🇬🇧 English</a>
</p>

---

## 📖 À propos

**ColocTraker** est une application web qui aide les colocataires à suivre et répartir équitablement les dépenses communes (loyer, courses, factures, etc.).

Chaque colocation regroupe plusieurs membres. Quand un membre paie une dépense, l'application calcule automatiquement qui doit combien à qui, **simplifie le nombre de remboursements** grâce à un algorithme de compensation des dettes, et garde une trace des règlements effectués. Un **système de réputation** récompense les membres qui quittent une colocation sans dette.

L'application inclut également un **espace d'administration** pour superviser les utilisateurs et consulter les statistiques de la plateforme.

---

## ✨ Fonctionnalités

### 👥 Gestion des colocations
- Création d'une colocation (l'auteur devient automatiquement **propriétaire / owner**)
- Tableau de bord listant toutes les colocations de l'utilisateur connecté
- Rôles via table pivot : **owner** et **member**
- Quitter une colocation (un membre) — avec impact sur la réputation
- Annuler / supprimer une colocation (propriétaire uniquement)
- Transfert de propriété à un autre membre
- Retrait d'un membre par le propriétaire

### ✉️ Invitations
- Invitation de nouveaux membres par e-mail
- Lien d'invitation sécurisé par **token unique**
- Statuts d'invitation (`pending`, accepté, refusé) et **date d'expiration**
- Acceptation / refus de l'invitation
- Envoi d'e-mails **asynchrone** via les files d'attente (Jobs + Mailable)

### 💸 Dépenses & catégories
- Ajout de dépenses (titre, description, montant, catégorie)
- **Catégories** de dépenses propres à chaque colocation
- Liste et suppression des dépenses
- Filtrage des dépenses

### ⚖️ Soldes & remboursements
- **Calcul automatique du solde** de chaque membre (ce qu'il a payé − sa part équitable)
- **Simplification des dettes** : algorithme qui minimise le nombre de transactions de remboursement (créanciers ↔ débiteurs)
- Suivi des **règlements** (`settlements`) et marquage « payé »

### 🏅 Système de réputation
- Score de réputation par utilisateur (`reputation_score`)
- **+1** lorsqu'un membre quitte une colocation **sans dette**
- **−1** lorsqu'il la quitte **avec une dette**
- Cas particulier : si le propriétaire retire un membre endetté, **la dette est automatiquement transférée au propriétaire** (un règlement `settlement` est créé)

### 🔐 Authentification (Laravel Breeze)
- Inscription, connexion, déconnexion
- **Le tout premier utilisateur inscrit est automatiquement promu administrateur global**
- Réinitialisation et confirmation de mot de passe
- Vérification d'e-mail
- Gestion du profil utilisateur

### 🛠️ Administration
- Tableau de bord de **statistiques**
- Gestion des utilisateurs
- **Bannissement / débannissement** d'un utilisateur (avec motif et date)
- Middleware dédié : `AdminMiddleware` (accès réservé) et `CheckBannedUser` (déconnexion automatique d'un compte banni)

---

## 🧰 Stack technique

| Domaine | Technologie |
|---|---|
| Framework | **Laravel 11** |
| Langage | **PHP 8.2+** |
| Vues | Blade + composants |
| Front-end | **Tailwind CSS 3**, **Alpine.js 3**, **Vite 5** |
| Base de données | **SQLite** par défaut (MySQL/PostgreSQL configurables) |
| Authentification | Laravel Breeze |
| Files d'attente | Driver `database` (envoi d'e-mails) |
| Tests | **Pest** (PHPUnit) |
| Outils | Laravel Pint, Laravel Sail, Tinker |

---

## 🗂️ Modèle de données

| Modèle | Rôle | Relations principales |
|---|---|---|
| **User** | Utilisateur (membre ou admin) | `colocation` (n-n), `depences`, `invitations`, `reputation_score`, `role`, `is_banned` |
| **Colocation** | Une colocation | `users` (n-n via pivot), `depences`, `categories`, `invitations`, `settlements` |
| **colocation_user** *(pivot)* | Appartenance membre↔colocation | `role` (owner/member), `left_at` |
| **Categories** | Catégorie de dépense par colocation | `colocation`, `depences` |
| **Depence** | Une dépense | `user`, `colocation`, `category` |
| **Settlement** | Un remboursement entre deux membres | `colocation`, `fromUser`, `toUser`, `amount`, `is_paid` |
| **Invitation** | Invitation à rejoindre une colocation | `colocation`, `token`, `status`, `expires_at` |

> 📊 Voir aussi les diagrammes fournis : [`ClassDiagram.png`](ClassDiagram.png) et [`use Case.png`](use%20Case.png).

---

## 🚀 Installation

### Prérequis
- PHP **8.2+** avec les extensions habituelles de Laravel
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & npm
- SQLite (par défaut) ou un autre SGBD

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://github.com/OUTERGA-MOUSTAFA/ColocTraker.git
cd ColocTraker

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances front-end
npm install

# 4. Préparer l'environnement
cp .env.example .env
php artisan key:generate

# 5. Créer la base SQLite (si elle n'existe pas)
#    (sous Windows PowerShell : New-Item database/database.sqlite -ItemType File)
touch database/database.sqlite

# 6. Lancer les migrations (et le seeder éventuel)
php artisan migrate --seed

# 7. Compiler les assets front-end
npm run build      # ou : npm run dev (mode développement)

# 8. Démarrer le serveur
php artisan serve
```

L'application est alors accessible sur **http://localhost:8000**.

> 💡 Pour que l'envoi des invitations par e-mail fonctionne, traitez la file d'attente :
> ```bash
> php artisan queue:work
> ```

---

## ⚙️ Configuration

La configuration se fait via le fichier `.env`. Points clés :

```dotenv
APP_NAME=ColocTraker
APP_URL=http://localhost:8000

# Base de données (SQLite par défaut)
DB_CONNECTION=sqlite

# Pour MySQL, décommentez et renseignez :
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=coloctraker
# DB_USERNAME=root
# DB_PASSWORD=

# E-mails (log par défaut — les invitations sont écrites dans les logs)
MAIL_MAILER=log

# Files d'attente
QUEUE_CONNECTION=database
```

---

## 🧪 Tests

Le projet utilise **Pest**.

```bash
php artisan test
# ou
./vendor/bin/pest
```

---

## 📁 Structure du projet

```
app/
├── Http/
│   ├── Controllers/        # Colocation, Depence, Settlement, Invitation, Categories, Admin/*
│   └── Middleware/         # AdminMiddleware, CheckBannedUser
├── Jobs/                   # SendInvitationEmail (file d'attente)
├── Mail/                   # InvitationMail
├── Models/                 # User, Colocation, Depence, Settlement, Invitation, Categories
├── Policies/               # ColocationPolicy, InvitationPolicy, UserPolicy
└── Services/
    ├── BalanceService.php      # Calcul & simplification des soldes
    └── ReputationService.php   # Gestion du score de réputation
database/
├── migrations/             # Schéma complet (colocations, dépenses, settlements, …)
└── seeders/
resources/views/            # Vues Blade (auth, colocation, admin, profil…)
routes/
├── web.php                 # Routes applicatives + administration
└── auth.php                # Routes d'authentification (Breeze)
```

---

## 🗺️ Aperçu des routes principales

| Méthode | URI | Description |
|---|---|---|
| `GET` | `/dashboard` | Liste des colocations de l'utilisateur |
| `POST` | `/colocation` | Créer une colocation |
| `GET` | `/colocation/{id}` | Détail d'une colocation (soldes, dépenses…) |
| `DELETE` | `/colocation/{id}/leave` | Quitter la colocation |
| `POST` | `/colocation/{id}/transfer-owner/{newOwner}` | Transférer la propriété |
| `POST` | `/colocation/{id}/depences` | Ajouter une dépense |
| `POST` | `/colocation/{id}/settlements/mark-paid` | Marquer un remboursement comme payé |
| `POST` | `/invitation/{id}` | Envoyer une invitation |
| `POST` | `/invitation/{token}/accept` | Accepter une invitation |
| `GET` | `/admin/statistics` | Statistiques (admin) |
| `POST` | `/admin/users/{user}/ban` | Bannir un utilisateur (admin) |

---

## 📄 Licence

Ce projet est construit avec Laravel, distribué sous licence [MIT](https://opensource.org/licenses/MIT).

---

<p align="center">
  Réalisé avec ❤️ et Laravel · <a href="README.en.md">Read this in English 🇬🇧</a>
</p>
