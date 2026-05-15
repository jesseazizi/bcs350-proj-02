<?php
require_once 'init.php';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Home</title>
    <link rel="stylesheet" href="css/styles.css">
    <script defer src="js/app.js"></script>
</head>
<body>
    <div class="container card">
        <h1>Welcome to the Quiz App</h1>
        <p>Sign in to start a new 10-question quiz and track your score.</p>

        <?php if ($user): ?>
            <p class="highlight">Hello, <?= htmlspecialchars($user['username']) ?>!</p>
            <div class="button-group">
                <a class="nav-btn" href="quiz.php?action=start" data-start-quiz>Start Quiz</a>
                <a class="nav-btn" href="profile.php">Profile</a>
                <a class="nav-btn" href="leaderboard.php">Leaderboard</a>
                <a class="nav-btn secondary" href="logout.php">Log Out</a>
            </div>
        <?php else: ?>
            <div class="button-group">
                <a class="nav-btn" href="login.php">Log In</a>
                <a class="nav-btn secondary" href="signup.php">Sign Up</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
