<?php
require 'db_connection.php';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = 'user';

    if (empty($username) || empty($password)) {
        $error = "Podaj nazwę użytkownika i hasło.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->fetch()) {
            $error = "Nazwa użytkownika jest już zajęta.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'role' => $role]);

            $success = "Rejestracja zakończona sukcesem! Możesz się teraz zalogować.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <style>
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 32px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"], input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            color: #555;
        }

        p a {
            color: #007BFF;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .error-message, .success-message {
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }
    </style>
</head>
<body>
<div class="form-container">
        <h2>Rejestracja</h2>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="success-message"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Hasło:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Zarejestruj się</button>
        </form>
        <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
    </div>
</body>
</html>
<?php
require 'footer.php';
?>
