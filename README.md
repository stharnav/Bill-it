# Bill It

> Ultimate solution for managing your business

**Bill It** is a free and open-source web-based business management system built with Laravel.  
It helps businesses manage billing, customers, and operations efficiently with flexible database support.

---

## Built With

- PHP
- Laravel
- MySQL
- MariaDB
- SQLite

---

## Features

- Invoice & billing management
- Product & service management
- Secure authentication system
- Fully web-based
- Multiple database support (MySQL, MariaDB, SQLite)
- Free & Open Source

---

## Requirements

- PHP >= 8.1
- Composer
- Apache or Nginx
- MySQL / MariaDB / SQLite

---

## Installation

```bash
git clone https://github.com/stharnav/Bill-it.git
cd bill-it
composer install
cp .env.example .env
php artisan key:generate
```

## ENV File

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=billit
DB_USERNAME=root
DB_PASSWORD=
```

## Terminal

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```
