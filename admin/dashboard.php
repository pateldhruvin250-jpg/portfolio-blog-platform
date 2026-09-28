<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

define('BASE_URL_OVERRIDE', true);
require_once __DIR__ . '/../includes/db.php';

$pdo = get_db();
$posts = $pdo->query(
    'SELECT posts.*, users.name AS author_name
     FROM posts JOIN users ON users.id = posts.user_id
     ORDER BY posts.created_at DESC'
)->fetchAll();

$pageTitle = 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="../public/index.php"><?= e(SITE_NAME) ?> — Admin</a>
        <nav>
            <a href="../public/index.php">View Site</a>
            <a href="create_post.php">New Post</a>
            <a href="../public/logout.php">Logout</a>
        </nav>
    </div>
</header>
<main class="container">
    <?php $successMsg = flash('success'); ?>
    <?php if ($successMsg): ?><div class="alert alert-success"><?= e($successMsg) ?></div><?php endif; ?>

    <h1>Manage Posts</h1>
    <table class="admin-table">
        <thead>
            <tr><th>Title</th><th>Author</th><th>Status</th><th>Created</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= e($post['title']) ?></td>
                <td><?= e($post['author_name']) ?></td>
                <td><?= e($post['status']) ?></td>
                <td><?= date('d M Y', strtotime($post['created_at'])) ?></td>
                <td>
                    <a href="edit_post.php?id=<?= (int)$post['id'] ?>">Edit</a>
                    |
                    <a href="delete_post.php?id=<?= (int)$post['id'] ?>"
                       onclick="return confirm('Delete this post?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($posts)): ?>
            <tr><td colspan="5">No posts yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
