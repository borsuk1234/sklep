<?php
require 'db_connection.php'; // Połączenie z bazą danych
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = 'user'; // Domyślna rola użytkownika

    if (empty($username) || empty($password)) {
        $error = "Podaj nazwę użytkownika i hasło.";
    } else {
        // Sprawdź, czy nazwa użytkownika jest unikalna
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->fetch()) {
            $error = "Nazwa użytkownika jest już zajęta.";
        } else {
            // Dodaj użytkownika do bazy
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
    <link rel="stylesheet" href="styles.css"> <!-- Plik CSS -->
</head>
<body>
    <h1>Rejestracja</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <form method="POST" action="register.php">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Zarejestruj się</button>
    </form>
    <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
</body>
</html>
<?php
require 'footer.php';
?>