<?php
/**
 * Authentication helpers: registration, login, logout, and simple
 * role-based access guards used by the admin section.
 */

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_admin(): bool
{
    $user = current_user();
    return $user !== null && $user['role'] === 'admin';
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        die('Forbidden: admin access only.');
    }
}

/**
 * Registers a new user. Returns [success(bool), message(string)].
 */
function register_user(string $name, string $email, string $password): array
{
    $name = trim($name);
    $email = trim(strtolower($email));

    if ($name === '' || $email === '' || $password === '') {
        return [false, 'All fields are required.'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [false, 'Please enter a valid email address.'];
    }
    if (strlen($password) < 8) {
        return [false, 'Password must be at least 8 characters long.'];
    }

    $pdo = get_db();

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return [false, 'An account with that email already exists.'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$name, $email, $hash, 'user']);

    return [true, 'Account created successfully. You can now log in.'];
}

/**
 * Logs a user in. Returns [success(bool), message(string)].
 */
function login_user(string $email, string $password): array
{
    $email = trim(strtolower($email));

    $pdo = get_db();
    $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return [false, 'Invalid email or password.'];
    }

    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];

    return [true, 'Logged in successfully.'];
}

function logout_user(): void
{
    $_SESSION = [];
    session_destroy();
}
