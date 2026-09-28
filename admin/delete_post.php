<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);

$pdo = get_db();
$stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
$stmt->execute([$id]);

flash('success', 'Post deleted.');
header('Location: dashboard.php');
exit;
