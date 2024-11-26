<?php
header('Content-Type: application/json');
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
        echo json_encode(['success' => false, 'message' => 'Brak dostępu']);
        exit;
    }

    $name = $_POST['product_name'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO produkty (nazwa) VALUES (:name)");
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Błąd dodawania produktu.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Metoda żądania nieprawidłowa.']);
}
?>