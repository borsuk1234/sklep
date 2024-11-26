<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Znajdź użytkownika po e-mailu
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generuj token
        $token = bin2hex(random_bytes(32));
        $expiresAt = (new DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');

        // Zapisz token do bazy danych
        $stmt = $pdo->prepare("INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
        $stmt->execute(['user_id' => $user['id'], 'token' => $token, 'expires_at' => $expiresAt]);

        // Wyślij e-mail z linkiem resetującym
        $resetLink = "http://localhost/reset_password.php?token=$token";
        mail($email, "Resetowanie hasła", "Kliknij tutaj, aby zresetować hasło: $resetLink");

        echo "E-mail z linkiem do resetowania hasła został wysłany.";
    } else {
        echo "Nie znaleziono użytkownika z tym adresem e-mail.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Resetowanie hasła</title>
</head>
<body>
    <form method="POST">
        <label>Adres e-mail: <input type="email" name="email" required></label><br>
        <button type="submit">Wyślij link resetujący</button>
    </form>
</body>
</html>
