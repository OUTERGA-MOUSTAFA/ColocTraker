<h1 align="center">🏠 ColocTraker</h1>

<p align="center">
  <strong>Track and split your shared household expenses, the easy way.</strong><br>
  Expense tracking · Automatic balance calculation · Simplified settlements · Reputation system
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3-38B2AC?logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?logo=alpinedotjs&logoColor=white" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Tests-Pest-5E2D7D" alt="Pest">
</p>

<p align="center">
  <a href="README.md">🇫🇷 Français</a> &nbsp;|&nbsp; 🇬🇧 <strong>English</strong>
</p>

---

## 📖 About

**ColocTraker** is a web application that helps roommates track and fairly split shared expenses (rent, groceries, bills, etc.).

Each shared house ("colocation") groups several members together. When a member pays for an expense, the app automatically computes who owes whom, **minimizes the number of repayments** through a debt-compensation algorithm, and keeps a record of completed settlements. A built-in **reputation system** rewards members who leave a shared house with no outstanding debt.

The application also ships with an **admin area** to oversee users and review platform statistics.

> 🌍 This is the English version. La version française est disponible dans [README.md](README.md).

---

## ✨ Features

### 👥 Shared-house management
- Create a shared house (the creator automatically becomes the **owner**)
- Dashboard listing all of the signed-in user's shared houses
- Roles via a pivot table: **owner** and **member**
- Leave a shared house (member) — affects reputation
- Cancel / delete a shared house (owner only)
- Transfer ownership to another member
- Remove a member (owner)

### ✉️ Invitations
- Invite new members by email
- Invitation link secured with a **unique token**
- Invitation statuses (`pending`, accepted, declined) and an **expiration date**
- Accept / decline an invitation
- **Asynchronous** email delivery through queues (Jobs + Mailable)

### 💸 Expenses & categories
- Add expenses (title, description, amount, category)
- Per-shared-house expense **categories**
- List and delete expenses
- Expense filtering

### ⚖️ Balances & settlements
- **Automatic balance calculation** for each member (what they paid − their fair share)
- **Debt simplification**: an algorithm that minimizes the number of repayment transactions (creditors ↔ debtors)
- Tracking of **settlements** with a "mark as paid" action

### 🏅 Reputation system
- Per-user reputation score (`reputation_score`)
- **+1** when a member leaves a shared house **with no debt**
- **−1** when they leave **with an outstanding debt**
- Special case: if the owner removes an indebted member, **the debt is automatically transferred to the owner** (a `settlement` is created)

### 🔐 Authentication (Laravel Breeze)
- Sign up, sign in, sign out
- **The very first registered user is automatically promoted to global administrator**
- Password reset and confirmation
- Email verification
- User profile management

### 🛠️ Administration
- **Statistics** dashboard
- User management
- **Ban / unban** a user (with reason and timestamp)
- Dedicated middleware: `AdminMiddleware` (restricted access) and `CheckBannedUser` (auto-logout of a banned account)

---

## 🧰 Tech stack

| Area | Technology |
|---|---|
| Framework | **Laravel 11** |
| Language | **PHP 8.2+** |
| Views | Blade + components |
| Front-end | **Tailwind CSS 3**, **Alpine.js 3**, **Vite 5** |
| Database | **SQLite** by default (MySQL/PostgreSQL configurable) |
| Authentication | Laravel Breeze |
| Queues | `database` driver (email delivery) |
| Testing | **Pest** (PHPUnit) |
| Tooling | Laravel Pint, Laravel Sail, Tinker |

---

## 🗂️ Data model

| Model | Purpose | Main relations |
|---|---|---|
| **User** | A user (member or admin) | `colocation` (n-n), `depences`, `invitations`, `reputation_score`, `role`, `is_banned` |
| **Colocation** | A shared house | `users` (n-n via pivot), `depences`, `categories`, `invitations`, `settlements` |
| **colocation_user** *(pivot)* | Member↔shared-house membership | `role` (owner/member), `left_at` |
| **Categories** | An expense category per shared house | `colocation`, `depences` |
| **Depence** | An expense | `user`, `colocation`, `category` |
| **Settlement** | A repayment between two members | `colocation`, `fromUser`, `toUser`, `amount`, `is_paid` |
| **Invitation** | An invitation to join a shared house | `colocation`, `token`, `status`, `expires_at` |

> 📊 See also the provided diagrams: [`ClassDiagram.png`](ClassDiagram.png) and [`use Case.png`](use%20Case.png).
>
> ℹ️ Note: some domain entities use French names in the codebase — `Depence` means *Expense* and `Colocation` means *shared house / flatshare*.

---

## 🚀 Installation

### Requirements
- PHP **8.2+** with the usual Laravel extensions
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & npm
- SQLite (default) or another database engine

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/OUTERGA-MOUSTAFA/ColocTraker.git
cd ColocTraker

# 2. Install PHP dependencies
composer install

# 3. Install front-end dependencies
npm install

# 4. Set up the environment
cp .env.example .env
php artisan key:generate

# 5. Create the SQLite database (if it does not exist)
#    (on Windows PowerShell: New-Item database/database.sqlite -ItemType File)
touch database/database.sqlite

# 6. Run the migrations (and the optional seeder)
php artisan migrate --seed

# 7. Build the front-end assets
npm run build      # or: npm run dev (development mode)

# 8. Start the server
php artisan serve
```

The application is then available at **http://localhost:8000**.

> 💡 For invitation emails to be sent, process the queue:
> ```bash
> php artisan queue:work
> ```

---

## ⚙️ Configuration

Configuration is done through the `.env` file. Key settings:

```dotenv
APP_NAME=ColocTraker
APP_URL=http://localhost:8000

# Database (SQLite by default)
DB_CONNECTION=sqlite

# For MySQL, uncomment and fill in:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=coloctraker
# DB_USERNAME=root
# DB_PASSWORD=

# Email (log by default — invitations are written to the logs)
MAIL_MAILER=log

# Queues
QUEUE_CONNECTION=database
```

---

## 🧪 Testing

The project uses **Pest**.

```bash
php artisan test
# or
./vendor/bin/pest
```

---

## 📁 Project structure

```
app/
├── Http/
│   ├── Controllers/        # Colocation, Depence, Settlement, Invitation, Categories, Admin/*
│   └── Middleware/         # AdminMiddleware, CheckBannedUser
├── Jobs/                   # SendInvitationEmail (queued)
├── Mail/                   # InvitationMail
├── Models/                 # User, Colocation, Depence, Settlement, Invitation, Categories
├── Policies/               # ColocationPolicy, InvitationPolicy, UserPolicy
└── Services/
    ├── BalanceService.php      # Balance calculation & debt simplification
    └── ReputationService.php   # Reputation score handling
database/
├── migrations/             # Full schema (colocations, expenses, settlements, …)
└── seeders/
resources/views/            # Blade views (auth, colocation, admin, profile…)
routes/
├── web.php                 # Application + admin routes
└── auth.php                # Authentication routes (Breeze)
```

---

## 🗺️ Main routes overview

| Method | URI | Description |
|---|---|---|
| `GET` | `/dashboard` | List the user's shared houses |
| `POST` | `/colocation` | Create a shared house |
| `GET` | `/colocation/{id}` | Shared-house detail (balances, expenses…) |
| `DELETE` | `/colocation/{id}/leave` | Leave the shared house |
| `POST` | `/colocation/{id}/transfer-owner/{newOwner}` | Transfer ownership |
| `POST` | `/colocation/{id}/depences` | Add an expense |
| `POST` | `/colocation/{id}/settlements/mark-paid` | Mark a settlement as paid |
| `POST` | `/invitation/{id}` | Send an invitation |
| `POST` | `/invitation/{token}/accept` | Accept an invitation |
| `GET` | `/admin/statistics` | Statistics (admin) |
| `POST` | `/admin/users/{user}/ban` | Ban a user (admin) |

---

## 📄 License

This project is built with Laravel and distributed under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">
  Built with ❤️ and Laravel · <a href="README.md">Lire en français 🇫🇷</a>
</p>
