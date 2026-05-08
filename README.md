<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License">
</p>

<h1 align="center">
  <strong>UniShop Manager</strong>
  <br>
  <sub>Professional Retail Shop Management System</sub>
</h1>

<p align="center">
  A complete, modern, and feature-rich retail shop management system built with Laravel 11. Manage your products, sales, purchases, customers, suppliers, expenses, and generate detailed reports — all in one place.
</p>

<p align="center">
  <a href="#features">Features</a> &bull;
  <a href="#requirements">Requirements</a> &bull;
  <a href="#quick-start">Quick Start</a> &bull;
  <a href="#local-setup">Local Setup (XAMPP)</a> &bull;
  <a href="#cpanel-deployment">cPanel Deployment</a> &bull;
  <a href="#tech-stack">Tech Stack</a>
</p>

---

## Features

### Core Modules
| # | Feature | Description |
|---|---------|-------------|
| 1 | **Authentication** | Secure login, register, password management with session-based auth |
| 2 | **Dashboard** | Real-time overview with sales charts, low stock alerts, top products |
| 3 | **POS / Sales** | Barcode search, cart system, instant checkout, invoice generation |
| 4 | **Products** | Full CRUD with categories, pricing, stock tracking, alert quantities |
| 5 | **Categories** | Organize products into hierarchical categories |
| 6 | **Customers** | Customer database with contact info and purchase history |
| 7 | **Suppliers** | Supplier management for purchase tracking |
| 8 | **Purchases** | Track stock purchases from suppliers with auto stock updates |
| 9 | **Expenses** | Daily expense tracking with categories and notes |
| 10 | **Reports** | Daily sales, monthly sales, profit analysis, stock reports |

### Key Highlights
- **Single User System** — Each user manages their own shop data independently
- **Dark Sidebar Navigation** — Modern, professional UI with inline SVG icons (no CDN dependency)
- **Real-time Dashboard** — Chart.js powered analytics with sales trends
- **POS with Barcode** — Fast selling with barcode search and cart management
- **Auto Stock Updates** — Stock adjusts automatically on every sale and purchase
- **Invoice Generation** — Professional printable invoices for every sale
- **Low Stock Alerts** — Get notified when products reach alert quantities
- **Responsive Design** — Works on desktop, tablet, and mobile devices
- **Glassmorphism UI** — Modern frosted glass navbar, gradient cards, smooth animations

---

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 8.1+ (recommended: 8.2 or 8.3) |
| MySQL | 5.7+ (recommended: 8.0) |
| Composer | 2.x |
| Web Server | Apache (with mod_rewrite) or Nginx |
| Extensions | openssl, pdo, pdo_mysql, mbstring, tokenizer, xml, ctype, json, fileinfo, zip |

---

## Quick Start (Production / Live Server)

### Step 1: Clone the Repository
```bash
git clone https://github.com/devfahimbd/unishop.git
cd unishop
```

### Step 2: Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:
```env
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

### Step 4: Run Migrations
```bash
php artisan migrate --force
```

### Step 5: Storage Link
```bash
php artisan storage:link
```

### Step 6: Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## Local Setup (XAMPP on Windows)

### Step 1: Install XAMPP
Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)

### Step 2: Start Services
Open XAMPP Control Panel and start **Apache** and **MySQL**.

### Step 3: Enable PHP Extensions
1. Open `C:\xampp\php\php.ini` in Notepad
2. Find and enable (remove `;` from start) these extensions:
   ```
   extension=fileinfo
   extension=zip
   extension=pdo_mysql
   extension=mysqli
   extension=openssl
   extension=mbstring
   ```
3. Save the file and restart Apache

### Step 4: Setup Project
Open Command Prompt (Terminal) and run:
```bash
cd C:\xampp\htdocs

# Extract/copy the project folder here
# Then:

cd unishop

copy .env.example .env

C:\xampp\php\php.exe artisan key:generate
```

### Step 5: Create Database
1. Open browser: `http://localhost/phpmyadmin`
2. Click **New** and create database: `unishop_manager`
3. Collation: `utf8mb4_unicode_ci`

### Step 6: Run Migrations
```bash
C:\xampp\php\php.exe artisan migrate
```

### Step 7: Start Server
```bash
C:\xampp\php\php.exe artisan serve
```

### Step 8: Open in Browser
Go to: `http://localhost:8000`

**Default URL:** `http://localhost:8000/register` (create your account first)

---

## cPanel Deployment (Live Hosting)

### Prerequisites
- Shared hosting or VPS with cPanel
- PHP 8.1+ enabled
- MySQL database

### Step 1: Upload Files
1. Login to cPanel → **File Manager**
2. Navigate to `public_html`
3. Upload the project ZIP file
4. **Extract** the ZIP file
5. Move all files from the extracted folder to `public_html` root
6. Your structure should look like:
   ```
   public_html/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── public/        ← THIS is your document root
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/
   ├── .env
   ├── artisan
   └── ...
   ```

### Step 2: Set Document Root to `public/`
1. In cPanel, go to **Domains** → **Manage Domains**
2. Click **Edit** next to your domain
3. Change **Document Root** from `public_html` to `public_html/public`
4. Save changes

**Alternative (using .htaccess):**
If you can't change document root, create `.htaccess` in `public_html`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Step 3: Create MySQL Database
1. In cPanel, go to **MySQL Databases**
2. Create a new database: `unishop_manager`
3. Create a new user with a strong password
4. Add the user to the database with **ALL PRIVILEGES**

### Step 4: Setup Environment
1. In File Manager, go to `public_html`
2. Rename `.env.example` to `.env` (or create new `.env` file)
3. Edit `.env` and update:
   ```env
   APP_NAME="UniShop Manager"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_DATABASE=unishop_manager
   DB_USERNAME=your_cpanel_db_user
   DB_PASSWORD=your_cpanel_db_password
   ```
4. Save the file

### Step 5: Run Commands via SSH or Terminal
Connect to your hosting via SSH (cPanel → Terminal) and run:
```bash
cd ~/public_html

# If no SSH, use cPanel PHP Console or set up PHP Cron for one-time execution
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 6: Set Permissions
In cPanel File Manager:
1. Right-click `storage/` folder → **Permissions** → set to `755`
2. Right-click `bootstrap/cache/` folder → **Permissions** → set to `755`

### Step 7: Setup Cron Job (for Scheduled Tasks)
1. In cPanel, go to **Cron Jobs**
2. Add a new cron job (every minute):
   ```
   /usr/local/bin/php -f /home/YOUR_USERNAME/public_html/artisan schedule:run >> /dev/null 2>&1
   ```
3. Replace `YOUR_USERNAME` with your actual cPanel username

### Step 8: SSL Certificate (HTTPS)
1. In cPanel, go to **SSL/TLS Status** or **Let's Encrypt**
2. Enable SSL for your domain
3. In `.env`, update: `APP_URL=https://yourdomain.com`

### Step 9: Access Your Application
Visit: `https://yourdomain.com/register`

Create your admin account and start using UniShop Manager!

---

## Tech Stack

| Technology | Purpose |
|-----------|---------|
| **Laravel 11** | PHP Framework (Backend) |
| **PHP 8.x** | Server-side Language |
| **MySQL** | Database |
| **Bootstrap 5.3** | Frontend Framework (CDN) |
| **Chart.js 4.4** | Dashboard Charts (CDN) |
| **jQuery 3.7** | AJAX Operations (CDN) |
| **Inter Font** | Typography (Google Fonts) |
| **Inline SVGs** | Icons (No CDN dependency) |
| **Blade Templates** | View Engine |

---

## Project Structure

```
unishop/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # 12 Controllers
│   │   └── Middleware/       # Auth Middleware
│   └── Models/              # 10 Eloquent Models
├── config/                  # App Configuration
├── database/
│   └── migrations/          # 12 Database Migrations
├── public/
│   ├── css/app.css          # Custom Styles
│   └── index.php            # Entry Point
├── resources/
│   └── views/               # 28+ Blade Templates
│       ├── layouts/         # Main Layout
│       ├── auth/            # Login & Register
│       ├── dashboard/       # Dashboard
│       ├── products/        # Product CRUD
│       ├── categories/      # Category CRUD
│       ├── customers/       # Customer Management
│       ├── suppliers/       # Supplier Management
│       ├── purchases/       # Purchase Management
│       ├── pos/             # Point of Sale
│       ├── expenses/        # Expense Tracking
│       ├── reports/         # Reports & Analytics
│       ├── invoice/         # Invoice Print
│       └── profile/         # User Profile
├── routes/
│   ├── web.php              # Web Routes
│   └── console.php          # Console Routes
├── .env.example             # Environment Template
├── artisan                  # CLI Entry Point
└── composer.json            # PHP Dependencies
```

---

## Important Notes

1. **Single User System**: This is designed for a single shop owner. Each user's data is completely isolated by `user_id`.
2. **No Admin Panel**: There is no multi-user admin system. The registered user is the shop owner.
3. **CDN Assets**: CSS and JS are loaded via CDN (Bootstrap, Chart.js, jQuery). No `npm build` is required.
4. **Inline SVG Icons**: All icons are inline SVGs — no external icon library dependency.
5. **Database**: Default database name is `unishop_manager`. You can change it in `.env`.

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| `Too few arguments to Schedule::command()` | Edit `routes/console.php` and remove the `Schedule::command()` call |
| `could not find driver (MySQL)` | Enable `extension=pdo_mysql` and `extension=mysqli` in `php.ini` |
| `ext-fileinfo missing` | Enable `extension=fileinfo` in `php.ini` |
| `storage/framework directories missing` | Create folders: `storage/framework/{sessions,views,cache}` and `storage/logs` |
| `500 Internal Server Error` | Check `.env` file exists, `APP_KEY` is set, and storage permissions are `755` |
| `404 Not Found on routes` | Make sure `public/` is the document root (not project root) |
| `Icons not showing` | Icons are inline SVGs — if missing, clear view cache: `php artisan view:clear` |

---

## License

This project is open-source software licensed under the [MIT License](LICENSE).

---

<p align="center">
  Built with ❤️ using Laravel 11
  <br>
  <sub>UniShop Manager &copy; 2025 — All Rights Reserved</sub>
</p>
