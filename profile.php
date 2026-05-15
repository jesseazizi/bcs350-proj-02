<?php
require_once 'init.php';
require_login();
$user = current_user();
$attempts = get_user_attempts($user['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container card">
        <h1><?= htmlspecialchars($user['username']) ?>'s Profile</h1>
        <p>Review your play history and quiz performance.</p>

        <?php if (empty($attempts)): ?>
            <p>You haven't played a quiz yet.</p>
        <?php else: ?>
            <div class="history-list">
                <?php foreach ($attempts as $attempt): ?>
                    <div class="history-item">
                        <strong><?= htmlspecialchars($attempt['num_correct_questions']) ?>/<?= htmlspecialchars($attempt['num_total_questions']) ?></strong>
                        <span><?= date('F j, Y \a\t g:i A', strtotime($attempt['played_at'])) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="button-group">
            <a class="nav-btn" href="quiz.php?action=start">Start New Quiz</a>
            <a class="nav-btn secondary" href="leaderboard.php">Leaderboard</a>
            <a class="nav-btn secondary" href="index.php">Home</a>
        </div>
    </div>
</body>
</html>
