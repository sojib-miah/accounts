# 🚀 Laravel Installation Guide

A complete step-by-step guide to install a fresh Laravel project with PHP, Composer, Node.js, and Vite.

---

# 📋 Requirements

Before installing Laravel, make sure you have:

| Software | Version |
| -------- | ------- |
| PHP      | >= 8.2  |
| Composer | Latest  |
| Node.js  | >= 20   |
| NPM      | Latest  |
| MySQL    | 8+      |
| Git      | Latest  |

Check installed versions:

```bash
php -v
composer -V
node -v
npm -v
git --version
```

---

# 📥 Clone Repository

```bash
git clone https://github.com/your-username/your-project.git
```

Go to project directory

```bash
cd your-project
```

---

# 📦 Install PHP Dependencies

```bash
composer install
```

---

# 📦 Install Node Dependencies

```bash
npm install
```

---

# ⚙️ Configure Environment

Copy the environment file.

```bash
cp .env.example .env
```

For Windows

```bash
copy .env.example .env
```

---

# 🔑 Generate Application Key

```bash
php artisan key:generate
```

---

# 🗄️ Configure Database

Update your `.env` file.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=
```

---

# 🚀 Run Database Migration

```bash
php artisan migrate
```

Run migrations with seeders

```bash
php artisan migrate --seed
```

Fresh migration

```bash
php artisan migrate:fresh --seed
```

---

# 📦 Build Frontend Assets

Development

```bash
npm run dev
```

Production

```bash
npm run build
```

---

# ▶️ Start Development Server

```bash
php artisan serve
```

Open

```
http://127.0.0.1:8000
```

---

# ⚡ Run Everything Together

Open two terminals.

### Terminal 1

```bash
php artisan serve
```

### Terminal 2

```bash
npm run dev
```

---

# 🧹 Useful Artisan Commands

Clear cache

```bash
php artisan optimize:clear
```

Clear config

```bash
php artisan config:clear
```

Clear route cache

```bash
php artisan route:clear
```

Clear view cache

```bash
php artisan view:clear
```

Cache configuration

```bash
php artisan config:cache
```

Cache routes

```bash
php artisan route:cache
```

List all routes

```bash
php artisan route:list
```

---

# 👤 Create Storage Link

```bash
php artisan storage:link
```

---

# 📁 File Permissions (Linux)

```bash
chmod -R 775 storage bootstrap/cache
```

---

# 🧪 Run Tests

```bash
php artisan test
```

or

```bash
php artisan test --parallel
```

---

# 🐳 Optional (Laravel Herd / Sail)

Laravel Sail

```bash
./vendor/bin/sail up -d
```

Run Artisan

```bash
./vendor/bin/sail artisan migrate
```

---

# 📦 Project Structure

```
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
vendor/
.env
artisan
composer.json
package.json
```

---

# 📚 Common Commands

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm run dev
php artisan serve
```

---

# ❗ Troubleshooting

### Clear everything

```bash
php artisan optimize:clear
composer dump-autoload
```

### Reinstall dependencies

```bash
rm -rf vendor
composer install
```

### Reinstall Node packages

```bash
rm -rf node_modules
npm install
```

---

# ❤️ Happy Coding

Made with ❤️ using Laravel.
