<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT password, role FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];
        $_SESSION['is_admin'] = $row['role'] === 'admin';

        echo json_encode(['success' => true, 'message' => 'Zalogowano pomyślnie.', 'role' => $row['role']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Błędna nazwa użytkownika lub hasło.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie.']);
}
?>
