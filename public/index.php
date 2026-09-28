<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$pageTitle = 'Home';
require_once __DIR__ . '/../includes/header.php';

$pdo = get_db();
$projects = $pdo->query('SELECT * FROM projects ORDER BY created_at DESC')->fetchAll();
?>

<section class="hero">
    <h1>Hi, I'm Dhruvin Patel</h1>
    <p>B.E. Information Technology student building backend systems, REST APIs, and full-stack projects.</p>
</section>

<section>
    <h2>Projects</h2>
    <div class="grid">
        <?php foreach ($projects as $project): ?>
            <div class="card">
                <h3><?= e($project['title']) ?></h3>
                <p><?= e($project['description']) ?></p>
                <p class="tech-stack"><?= e($project['tech_stack']) ?></p>
                <div class="card-links">
                    <?php if ($project['github_url']): ?>
                        <a href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener">GitHub</a>
                    <?php endif; ?>
                    <?php if ($project['project_url']): ?>
                        <a href="<?= e($project['project_url']) ?>" target="_blank" rel="noopener">Live Demo</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($projects)): ?>
            <p>No projects added yet.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
