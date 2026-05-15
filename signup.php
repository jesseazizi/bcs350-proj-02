<?php
require_once 'init.php';

if (current_user()) {
    header('Location: index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Both username and password are required.';
    } elseif (strlen($username) < 3 || strlen($password) < 6) {
        $error = 'Username must be at least 3 characters and password at least 6 characters.';
    } elseif (user_by_username($username)) {
        $error = 'This username is already taken. Please choose another one.';
    } else {
        $pdo = db_connect();
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);

        $_SESSION['user'] = [
            'id' => $pdo->lastInsertId(),
            'username' => $username,
        ];

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container card">
        <h1>Sign Up</h1>
        <p>Create an account to save your quiz history.</p>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="signup.php">
            <p>
                <label for="username">Username</label><br>
                <input id="username" type="text" name="username" class="login-signup-field" maxlength="50" required>
            </p>
            <p>
                <label for="password">Password</label><br>
                <input id="password" type="password" name="password" class="login-signup-field" required>
            </p>
            <button class="start-btn" type="submit">Sign Up</button>
        </form>

        <p><a class="link" href="login.php">Already have an account?</a></p>
    </div>
</body>
</html>
