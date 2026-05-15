<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'quiz_app');
define('DB_USER', 'root');
define('DB_PASS', '');
define('QUESTIONS_FILE', __DIR__ . '/questions.json');

function db_connect() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $exception) {
        http_response_code(500);
        echo 'Unable to connect to the database. Please check `init.php` and your MySQL settings.';
        exit;
    }

    return $pdo;
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function require_login() {
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

function flash($name, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$name] = $message;
        return;
    }

    if (!empty($_SESSION['flash'][$name])) {
        $message = $_SESSION['flash'][$name];
        unset($_SESSION['flash'][$name]);
        return $message;
    }

    return null;
}

function load_questions() {
    static $questions = null;
    if ($questions !== null) {
        return $questions;
    }

    $json = @file_get_contents(QUESTIONS_FILE);
    $questions = $json ? json_decode($json, true) : [];
    return is_array($questions) ? $questions : [];
}

function select_quiz_questions($count = 10) {
    $questions = load_questions();
    $total = count($questions);

    if ($total <= $count) {
        return array_values($questions);
    }

    $keys = array_rand($questions, $count);
    if (!is_array($keys)) {
        $keys = [$keys];
    }

    $selected = [];
    foreach ($keys as $key) {
        $question = $questions[$key];
        $question['id'] = $key;
        $selected[] = $question;
    }

    return $selected;
}

function quiz_started() {
    return !empty($_SESSION['quiz']) && is_array($_SESSION['quiz']);
}

function quiz_completed() {
    return quiz_started() && !empty($_SESSION['quiz']['completed']);
}

function start_quiz() {
    $_SESSION['quiz'] = [
        'questions' => select_quiz_questions(10),
        'current' => 0,
        'score' => 0,
        'answers' => [],
        'started_at' => time(),
        'completed' => false,
        'saved' => false,
    ];
}

function current_question() {
    if (!quiz_started()) {
        return null;
    }

    return $_SESSION['quiz']['questions'][$_SESSION['quiz']['current']] ?? null;
}

function answer_current_question($answer) {
    if (!quiz_started()) {
        return;
    }

    $question = current_question();
    if (!$question) {
        return;
    }

    $correctAnswer = $question['answer'] ?? '';
    $isCorrect = strcasecmp(trim($answer), trim($correctAnswer)) === 0;

    $_SESSION['quiz']['answers'][] = [
        'question' => $question['question'],
        'selected' => $answer,
        'correctAnswer' => $correctAnswer,
        'isCorrect' => $isCorrect,
        'choices' => [
            'A' => $question['A'],
            'B' => $question['B'],
            'C' => $question['C'],
            'D' => $question['D'],
        ],
    ];

    if ($isCorrect) {
        $_SESSION['quiz']['score'] += 1;
    }

    $_SESSION['quiz']['current']++;
    if ($_SESSION['quiz']['current'] >= count($_SESSION['quiz']['questions'])) {
        $_SESSION['quiz']['completed'] = true;
    }
}

function save_quiz_attempt() {
    if (!quiz_started() || !quiz_completed() || empty($_SESSION['user']) || !empty($_SESSION['quiz']['saved'])) {
        return;
    }

    $pdo = db_connect();
    $stmt = $pdo->prepare('INSERT INTO quiz_attempts (user_id, num_correct_questions, num_total_questions) VALUES (?, ?, ?)');
    $stmt->execute([
        $_SESSION['user']['id'],
        $_SESSION['quiz']['score'],
        count($_SESSION['quiz']['questions']),
    ]);

    $_SESSION['quiz']['saved'] = true;
}

function get_leaderboard($limit = 10) {
    $pdo = db_connect();
    $stmt = $pdo->prepare(
        'SELECT u.username, qa.num_correct_questions, qa.num_total_questions, qa.played_at
        FROM quiz_attempts qa
        JOIN users u ON qa.user_id = u.id
        ORDER BY qa.num_correct_questions DESC, qa.played_at DESC
        LIMIT ?'
    );
    $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_user_attempts($userId) {
    $pdo = db_connect();
    $stmt = $pdo->prepare('SELECT num_correct_questions, num_total_questions, played_at FROM quiz_attempts WHERE user_id = ? ORDER BY played_at DESC');
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function user_by_username($username) {
    $pdo = db_connect();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    return $stmt->fetch();
}
