<?php
require_once 'init.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedAnswer = $_POST['answer'] ?? '';

    if ($selectedAnswer === '') {
        flash('quiz_error', 'Please choose an answer before continuing.');
        header('Location: quiz.php');
        exit;
    }

    answer_current_question($selectedAnswer);

    if (quiz_completed()) {
        header('Location: results.php');
        exit;
    }

    header('Location: quiz.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'start') {
    start_quiz();
    header('Location: quiz.php');
    exit;
}

$question = current_question();
$errorMessage = flash('quiz_error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container card">
        <h1>Quiz</h1>

        <?php if (!$question): ?>
            <p>Ready to start a new 10-question quiz?</p>
            <a class="nav-btn" href="quiz.php?action=start">Start Quiz</a>
            <p class="subtext"><a href="index.php">Back to home</a></p>
        <?php else: ?>
            <p>Question <?= $_SESSION['quiz']['current'] + 1 ?> of <?= count($_SESSION['quiz']['questions']) ?></p>

            <?php if ($errorMessage): ?>
                <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>

            <div class="question-card">
                <h2><?= htmlspecialchars($question['question']) ?></h2>
                <form method="POST" action="quiz.php">
                    <?php foreach (['A', 'B', 'C', 'D'] as $option): ?>
                        <?php if (!empty($question[$option])): ?>
                            <label class="quiz-option">
                                <input type="radio" name="answer" value="<?= $option ?>">
                                <strong><?= $option ?>.</strong> <?= htmlspecialchars($question[$option]) ?>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <button class="start-btn" type="submit">Submit Answer</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
