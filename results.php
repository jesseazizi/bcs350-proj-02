<?php
require_once 'init.php';
require_login();

if (!quiz_started() || !quiz_completed()) {
    header('Location: quiz.php');
    exit;
}

save_quiz_attempt();
$quiz = $_SESSION['quiz'];
$score = $quiz['score'];
$total = count($quiz['questions']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container card">
        <h1>Results</h1>
        <p>You scored <strong><?= $score ?></strong> out of <strong><?= $total ?></strong>.</p>

        <div class="result-summary">
            <?php foreach ($quiz['answers'] as $index => $answer): ?>
                <div class="result-row <?= $answer['isCorrect'] ? 'correct' : 'incorrect' ?>">
                    <div><strong>Q<?= $index + 1 ?>:</strong> <?= htmlspecialchars($answer['question']) ?></div>
                    <div>Your answer: <?= htmlspecialchars($answer['selected']) ?> (<?= htmlspecialchars($answer['choices'][$answer['selected']] ?? '') ?>)</div>
                    <div>Correct answer: <?= htmlspecialchars($answer['correctAnswer']) ?> (<?= htmlspecialchars($answer['choices'][$answer['correctAnswer']] ?? '') ?>)</div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="button-group">
            <a class="nav-btn" href="quiz.php?action=start">Play Again</a>
            <a class="nav-btn secondary" href="profile.php">View Profile</a>
            <a class="nav-btn secondary" href="leaderboard.php">Leaderboard</a>
        </div>
    </div>
</body>
</html>
