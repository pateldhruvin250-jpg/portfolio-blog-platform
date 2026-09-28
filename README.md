# Personal Portfolio & Blog Platform

A full-stack PHP + MySQL web application combining a project portfolio
with a simple blog. Built with plain PHP (no framework) using PDO for
all database access, session-based authentication, and role-based
access control for the admin area.

## Features

- Portfolio section listing projects (title, description, tech stack, links)
- Blog with list view and individual post pages
- User registration and login (passwords hashed with `password_hash`, bcrypt)
- Role-based access: only `admin` users can create/edit/delete posts
- Admin dashboard to manage posts (CRUD)
- All queries use PDO prepared statements (no SQL injection risk)
- Output escaped with `htmlspecialchars` everywhere (no XSS from user content)

## Tech Stack

PHP 8+, MySQL, PDO, HTML5, CSS3, native PHP sessions — no framework, no Composer dependencies.

## Project Structure

```
portfolio-blog-platform/
├── public/                # Publicly accessible pages (set this as your web root)
│   ├── index.php           # Portfolio home page
│   ├── blog.php             # Blog listing
│   ├── post.php              # Single blog post
│   ├── register.php
│   ├── login.php
│   ├── logout.php
│   └── css/style.css
├── admin/                 # Admin-only pages (protected by require_admin())
│   ├── dashboard.php
│   ├── create_post.php
│   ├── edit_post.php
│   └── delete_post.php
├── includes/
│   ├── config.php           # DB credentials + site settings
│   ├── db.php                # PDO connection
│   ├── auth.php               # Register/login/logout/role guards
│   ├── functions.php          # Helpers (escaping, slugs, flash messages)
│   ├── header.php
│   └── footer.php
└── database/
    └── schema.sql            # Full DB schema + sample data
```

## Setup (XAMPP / local PHP)

1. **Install XAMPP** (or any Apache/Nginx + PHP + MySQL stack) and start Apache + MySQL.
2. **Copy this folder** into `htdocs/` (XAMPP) so it's reachable, e.g. `htdocs/portfolio-blog-platform/`.
3. **Create the database:**
   ```bash
   mysql -u root -p < database/schema.sql
   ```
   or import `database/schema.sql` via phpMyAdmin.
4. **Set your DB credentials** in `includes/config.php` (defaults match a fresh XAMPP install: user `root`, empty password).
5. **Visit** `http://localhost/portfolio-blog-platform/public/index.php` in your browser.

## Creating your first admin

No admin account is pre-seeded (shipping a hard-coded password hash in
a public repo is bad practice). Instead:

1. Go to `/public/register.php` and create a normal account.
2. Promote it to admin directly in MySQL:
   ```sql
   UPDATE users SET role = 'admin' WHERE email = 'your@email.com';
   ```
3. Log out and back in — you'll now see an **Admin** link in the nav, leading to the post dashboard.

## Security notes

- Passwords are never stored in plain text — `password_hash()` / `password_verify()` (bcrypt) are used.
- All SQL uses PDO prepared statements.
- All dynamic output is escaped via `htmlspecialchars()` before being printed.
- Admin pages check both login state and role before allowing access.

## Possible Extensions

- Image upload for post cover images and project screenshots (the `public/uploads/` folder is ready for this)
- Comments on blog posts
- Pagination for the blog list
- Move from raw PDO to a lightweight router for cleaner URLs
