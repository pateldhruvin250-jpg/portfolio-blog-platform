<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$success, $message] = register_user(
        $_POST['name'] ?? '',
        $_POST['email'] ?? '',
        $_POST['password'] ?? ''
    );

    if ($success) {
        flash('success', $message);
        header('Location: login.php');
        exit;
    }
    $error = $message;
}

$pageTitle = 'Register';
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Create an Account</h1>

<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<form method="post" class="form">
    <label>Name
        <input type="text" name="name" required value="<?= e($_POST['name'] ?? '') ?>">
    </label>
    <label>Email
        <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
    </label>
    <label>Password
        <input type="password" name="password" required minlength="8">
    </label>
    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Log in</a></p>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
