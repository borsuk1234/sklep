<?php
header('Content-Type: application/json');
session_start();
require 'db_connection.php';

if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Brak dostępu']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['is_visible'])) {
    $productId = $_POST['product_id'];
    $isVisible = $_POST['is_visible'];

    try {
        $stmt = $pdo->prepare("UPDATE produkty SET is_visible = :is_visible WHERE id = :product_id");
        $stmt->execute(['is_visible' => $isVisible, 'product_id' => $productId]);

        echo json_encode(['success' => true, 'message' => 'Widoczność została zaktualizowana.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Błąd aktualizacji widoczności: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie.']);
}
?>