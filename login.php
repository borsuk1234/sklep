<?php
require 'db_connection.php';
require 'header.php'; // Połączenie z bazą danych

// Obsługa formularza logowania
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Sprawdź, czy podano dane logowania
    if (empty($username) || empty($password)) {
        $error = "Podaj nazwę użytkownika i hasło.";
    } else {
        // Pobierz użytkownika z bazy danych
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Sprawdź dane logowania
        if ($user && password_verify($password, $user['password'])) {
            // Ustawienie sesji użytkownika
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = ($user['role'] === 'admin'); // Weryfikacja roli

            // Przekierowanie po zalogowaniu
            if ($user['role'] === 'admin') {
                header('Location: admin_panel.php'); // Panel administratora
            } else {
                header('Location: index.php'); // Strona główna dla użytkowników
            }
            exit();
        } else {
            $error = "Nieprawidłowa nazwa użytkownika lub hasło.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="styles.css"> <!-- Plik CSS -->
</head>
<body>
    <h1>Logowanie</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Zaloguj się</button>
        <a href="password_reset_request.php" class="btn-reset-password">Zapomniałeś hasła?</a>
    </form>
    <p>Nie masz jeszcze konta? <a href="register.php">Zarejestruj się</a></p>
</body>
</html>
<?php
require 'footer.php';
?>
