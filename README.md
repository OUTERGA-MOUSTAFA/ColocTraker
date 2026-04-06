🏠 TaskBoard - Gestion de Colocations & Partage de Dépenses
PHPLaravelMySQLLicense

TaskBoard est une application web complète développée avec le framework Laravel, conçue pour simplifier la gestion des colocations et le suivi des dépenses partagées. Elle permet de calculer automatiquement les soldes de chacun, de gérer les invitations sécurisées, et inclut un système de réputation unique pour garantir la confiance entre colocataires.

✨ Fonctionnalités Clés
👥 Gestion des Colocations
Création, annulation et gestion complète des colocations.
Système d'invitation sécurisé par Token unique envoyé par email.
Restriction intelligente : Un utilisateur ne peut appartenir qu'à une seule colocation active à la fois.
Gestion des départs (avec historique left_at).
💰 Suivi des Dépenses
Ajout/suppression de dépenses (Titre, Montant, Date, Catégorie, Payeur).
Calculs automatiques des parts individuelles et des soldes.
Vue simplifiée du type "Qui doit quoi à qui".
Filtrage des dépenses par mois et statistiques par catégorie.
⚖️ Système de Réputation & Règles Métier
+1 si un membre quitte la colocation sans dette.
-1 si un membre quitte avec des dettes non réglées.
Règle de l'Owner : Si un propriétaire exclut un membre endetté, la dette est automatiquement transférée à l'Owner (ajustement interne).
🛡️ Administration & Sécurité
3 Rôles distincts : Member, Owner, Global Admin.
Le premier utilisateur inscrit devient automatiquement Global Admin.
Dashboard d'administration (Statistiques globales, gestion des bannis).
Déconnexion automatique et blocage d'accès pour les utilisateurs bannis.
🛠️ Stack Technique
Architecture : Monolithique MVC (Laravel)
Backend : PHP 8.x
Base de données : MySQL / PostgreSQL (Gérée via Migrations)
ORM : Eloquent (hasMany, belongsToMany)
Authentification : Laravel Breeze / Jetstream
Frontend : Blade Templates, Tailwind CSS (implicit via Breeze)
👤 Acteurs et Rôles
Rôle	Description
Member	Membre standard. Peut ajouter des dépenses, voir ses soldes, marquer un paiement et quitter la colocation.
Owner	Créateur de la colocation. Peut inviter/retirer des membres, gérer les catégories et annuler la colocation.
Global Admin	Administrateur de la plateforme. Accède aux statistiques globales et peut bannir/débannir n'importe quel utilisateur.
🚀 Scénarios d'Implémentation Principaux
Invitation : L'Owner génère un token. Le système vérifie la correspondance de l'email et s'assure que l'invité n'a pas déjà une colocation active avant de l'ajouter.
Dépense Commune : Chaque ajout de dépense déclenche le recalcul instantané des balances de tous les membres actifs.
Départ avec dette : Application de la pénalité de réputation et redistribution de la dette (selon qu'elle soit volontaire ou forcée par l'Owner).
Paiement : Action "Marquer payé" depuis la liste des settlements pour réduire les dettes de manière simplifiée.
📦 Installation & Lancement
Suivez ces étapes pour lancer le projet en local :

Prérequis : PHP, Composer, MySQL/PostgreSQL, Node.js & NPM

# 1. Cloner le dépôtgit clone https://github.com/OUTERGA-MOUSTAFA/TaskBoard.git# 2. Entrer dans le dossier du projetcd TaskBoard# 3. Installer les dépendances PHPcomposer install# 4. Installer les dépendances Frontend (si nécessaire)npm install && npm run build# 5. Configurer le fichier d'environnementcp .env.example .envphp artisan key:generate# 6. Configurer votre base de données dans le fichier .env# DB_DATABASE=taskboard# DB_USERNAME=root# DB_PASSWORD=your_password# 7. Exécuter les migrationsphp artisan migrate# 8. (Optionnel) Peupler la base de données avec des données de testphp artisan db:seed# 9. Lancer le serveur localphp artisan serve
💡 Note : Le premier compte créé sur l'application héritera automatiquement du rôle Global Admin.

🔮 Hors Périmètre (Bonus / Évolutions futures)
Ces fonctionnalités ne sont pas incluses dans la V1 mais constituent des pistes d'amélioration :

 Intégration de paiements réels via Stripe
 Notifications en temps réel (Websockets / Pusher)
 Calendrier des dépenses récurrentes
 Export de données (PDF / Excel)
📄 Licence
Ce projet est sous licence MIT. Vous êtes libre de l'utiliser et de le modifier.
