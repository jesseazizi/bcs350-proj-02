<?php
require_once 'init.php';
$leaders = get_leaderboard(10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container card">
        <h1>Leaderboard</h1>
        <p>Top 10 quiz performances.</p>

        <?php if (empty($leaders)): ?>
            <p>No quiz attempts have been recorded yet.</p>
        <?php else: ?>
            <div class="leaderboard-table">
                <div class="leaderboard-row header">
                    <span>Rank</span>
                    <span>Player</span>
                    <span>Score</span>
                    <span>Date</span>
                </div>
                <?php foreach ($leaders as $index => $leader): ?>
                    <div class="leaderboard-row">
                        <span>#<?= $index + 1 ?></span>
                        <span><?= htmlspecialchars($leader['username']) ?></span>
                        <span><?= htmlspecialchars($leader['num_correct_questions']) ?>/<?= htmlspecialchars($leader['num_total_questions']) ?></span>
                        <span><?= date('M j, Y', strtotime($leader['played_at'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="button-group">
            <a class="nav-btn" href="index.php">Home</a>
            <a class="nav-btn secondary" href="profile.php">Profile</a>
        </div>
    </div>
</body>
</html>
