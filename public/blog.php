<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Blog';
require_once __DIR__ . '/../includes/header.php';

$pdo = get_db();
$stmt = $pdo->query(
    "SELECT posts.*, users.name AS author_name
     FROM posts
     JOIN users ON users.id = posts.user_id
     WHERE posts.status = 'published'
     ORDER BY posts.created_at DESC"
);
$posts = $stmt->fetchAll();
?>

<h1>Blog</h1>

<?php foreach ($posts as $post): ?>
    <article class="post-preview">
        <h2><a href="post.php?slug=<?= urlencode($post['slug']) ?>"><?= e($post['title']) ?></a></h2>
        <p class="meta">By <?= e($post['author_name']) ?> on <?= date('d M Y', strtotime($post['created_at'])) ?></p>
        <p><?= e(mb_strimwidth(strip_tags($post['content']), 0, 200, '...')) ?></p>
    </article>
<?php endforeach; ?>

<?php if (empty($posts)): ?>
    <p>No blog posts published yet.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
