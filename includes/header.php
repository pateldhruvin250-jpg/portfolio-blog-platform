<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?><?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="index.php"><?= e(SITE_NAME) ?></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="blog.php">Blog</a>
            <?php if (is_logged_in()): ?>
                <?php if (is_admin()): ?>
                    <a href="../admin/dashboard.php">Admin</a>
                <?php endif; ?>
                <a href="logout.php">Logout (<?= e(current_user()['name']) ?>)</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
    <?php $successMsg = flash('success'); $errorMsg = flash('error'); ?>
    <?php if ($successMsg): ?><div class="alert alert-success"><?= e($successMsg) ?></div><?php endif; ?>
    <?php if ($errorMsg): ?><div class="alert alert-error"><?= e($errorMsg) ?></div><?php endif; ?>
