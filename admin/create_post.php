<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

require_admin();

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';

    if ($title === '' || $content === '') {
        $error = 'Title and content are required.';
    } else {
        $slug = slugify($title);
        $pdo = get_db();

        // Ensure slug uniqueness
        $base = $slug;
        $i = 1;
        $check = $pdo->prepare('SELECT id FROM posts WHERE slug = ?');
        while (true) {
            $check->execute([$slug]);
            if (!$check->fetch()) break;
            $slug = $base . '-' . (++$i);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO posts (user_id, title, slug, content, status) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([current_user()['id'], $title, $slug, $content, $status]);

        flash('success', 'Post created.');
        header('Location: dashboard.php');
        exit;
    }
}

$pageTitle = 'New Post';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<main class="container">
    <h1>New Post</h1>
    <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="form">
        <label>Title
            <input type="text" name="title" required value="<?= e($_POST['title'] ?? '') ?>">
        </label>
        <label>Content
            <textarea name="content" rows="10" required><?= e($_POST['content'] ?? '') ?></textarea>
        </label>
        <label>Status
            <select name="status">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </label>
        <button type="submit">Save Post</button>
        <a href="dashboard.php">Cancel</a>
    </form>
</main>
</body>
</html>
