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
        $error = 'Please enter both username and password.';
    } else {
        $user = user_by_username($username);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
            ];
            header('Location: index.php');
            exit;
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container card">
        <h1>Log In</h1>
        <p>Enter your account credentials.</p>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <p>
                <label for="username">Username</label><br>
                <input id="username" type="text" name="username" class="login-signup-field" maxlength="50" required>
            </p>
            <p>
                <label for="password">Password</label><br>
                <input id="password" type="password" name="password" class="login-signup-field" required>
            </p>
            <button class="start-btn" type="submit">Log In</button>
        </form>

        <p><a class="link" href="signup.php">Create a new account</a></p>
    </div>
</body>
</html>
