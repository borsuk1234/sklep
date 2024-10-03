<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $is_admin = false;

    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $hashed_password = $row['password'];
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;

            if ($username == 'admin') {
                $is_admin = true;
                $_SESSION['is_admin'] = 1;
            }
            else {
                $_SESSION['is_admin'] = 0;
            }

            echo json_encode(['success' => true, 'message' => 'Zalogowano pomyślnie.', 'username' => $username, 'is_admin' => $is_admin]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Błędna nazwa użytkownika lub hasło.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Błędna nazwa użytkownika lub hasło.']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie.']);
    exit();
}
?>
