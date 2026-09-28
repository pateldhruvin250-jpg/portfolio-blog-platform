<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$success, $message] = login_user($_POST['email'] ?? '', $_POST['password'] ?? '');

    if ($success) {
        header('Location: index.php');
        exit;
    }
    $error = $message;
}

$pageTitle = 'Login';
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Login</h1>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<form method="post" class="form">
    <label>Email
        <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
    </label>
    <label>Password
        <input type="password" name="password" required>
    </label>
    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register</a></p>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
