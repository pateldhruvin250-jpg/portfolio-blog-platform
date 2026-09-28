-- Personal Portfolio & Blog Platform — Database Schema
-- Import via: mysql -u root -p portfolio_blog < database/schema.sql
-- or through phpMyAdmin (XAMPP) -> Import tab.

CREATE DATABASE IF NOT EXISTS portfolio_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_blog;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    cover_image VARCHAR(255) DEFAULT NULL,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    tech_stack VARCHAR(255) NOT NULL,
    project_url VARCHAR(255) DEFAULT NULL,
    github_url VARCHAR(255) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- No admin account is seeded here on purpose (a hard-coded password hash
-- in a public repo is a bad practice). Instead:
--   1. Register a normal account through the site's /register.php form.
--   2. Promote it to admin by running:
--        UPDATE users SET role = 'admin' WHERE email = 'you@example.com';
-- See README.md "Creating your first admin" for the full steps.

-- A couple of sample rows so the site isn't empty on first run
INSERT INTO projects (title, description, tech_stack, project_url, github_url)
VALUES
('Student Management System', 'Backend system for managing student records, attendance, and grades via REST APIs.', 'Laravel, PHP, MySQL, REST API', NULL, 'https://github.com/pateldhruvin250-jpg/student-management-system'),
('Task Automation Tool', 'Python automation tool that pulls exchange-rate data from a third-party API and auto-generates reports.', 'Python, REST API, SQLite', NULL, 'https://github.com/pateldhruvin250-jpg/task-automation-tool');

INSERT INTO posts (user_id, title, slug, content, status)
VALUES (1, 'Welcome to my portfolio', 'welcome-to-my-portfolio',
'This is the first post on my new portfolio and blog platform, built from scratch with PHP and MySQL.',
'published');
