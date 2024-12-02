<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];

    
    $stmt = $pdo->prepare("SELECT * FROM password_reset_tokens WHERE token = :token AND expires_at > NOW()");
    $stmt->execute(['token' => $token]);
    $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resetRequest) {
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :user_id");
        $stmt->execute(['password' => $hashedPassword, 'user_id' => $resetRequest['user_id']]);

        
        $stmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE token = :token");
        $stmt->execute(['token' => $token]);

        echo "Hasło zostało pomyślnie zresetowane.";
    } else {
        echo "Nieprawidłowy lub wygasły token.";
    }
} elseif (isset($_GET['token'])) {
   
    $token = $_GET['token'];
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Resetowanie hasła</title>
    </head>
    <body>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label>Nowe hasło: <input type="password" name="password" required></label><br>
            <button type="submit">Resetuj hasło</button>
        </form>
    </body>
    </html>
    <?php
} else {
    echo "Brak dostępu.";
}
?>
