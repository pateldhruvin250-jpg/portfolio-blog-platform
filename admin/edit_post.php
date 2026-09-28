<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$pdo = get_db();

$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    die('Post not found.');
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';

    if ($title === '' || $content === '') {
        $error = 'Title and content are required.';
    } else {
        $update = $pdo->prepare(
            'UPDATE posts SET title = ?, content = ?, status = ? WHERE id = ?'
        );
        $update->execute([$title, $content, $status, $id]);

        flash('success', 'Post updated.');
        header('Location: dashboard.php');
        exit;
    }
}

$pageTitle = 'Edit Post';
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
    <h1>Edit Post</h1>
    <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="form">
        <label>Title
            <input type="text" name="title" required value="<?= e($_POST['title'] ?? $post['title']) ?>">
        </label>
        <label>Content
            <textarea name="content" rows="10" required><?= e($_POST['content'] ?? $post['content']) ?></textarea>
        </label>
        <label>Status
            <select name="status">
                <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
            </select>
        </label>
        <button type="submit">Update Post</button>
        <a href="dashboard.php">Cancel</a>
    </form>
</main>
</body>
</html>
