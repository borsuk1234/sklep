<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $hashed_password]);

    echo json_encode(['success' => true, 'message' => 'Użytkownik zarejestrowany pomyślnie.']);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie.']);
    exit();
}
?>
