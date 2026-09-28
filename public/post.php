<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$slug = $_GET['slug'] ?? '';

$pdo = get_db();
$stmt = $pdo->prepare(
    "SELECT posts.*, users.name AS author_name
     FROM posts
     JOIN users ON users.id = posts.user_id
     WHERE posts.slug = ? AND posts.status = 'published'"
);
$stmt->execute([$slug]);
$post = $stmt->fetch();

$pageTitle = $post ? $post['title'] : 'Post not found';
require_once __DIR__ . '/../includes/header.php';

if (!$post) {
    echo '<p>Sorry, that post could not be found.</p>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}
?>

<article class="post-full">
    <h1><?= e($post['title']) ?></h1>
    <p class="meta">By <?= e($post['author_name']) ?> on <?= date('d M Y', strtotime($post['created_at'])) ?></p>
    <div class="post-content"><?= nl2br(e($post['content'])) ?></div>
</article>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
