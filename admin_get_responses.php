<?php
header('Content-Type: application/json');
session_start();

require 'db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Błąd połączenia z bazą danych: ' . $e->getMessage()]);
    exit();
}

if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Brak dostępu']);
    exit();
}

if (!isset($_GET['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie']);
    exit();
}

$productId = $_GET['product_id'];

$stmt = $pdo->prepare("SELECT * FROM odpowiedzi WHERE product_id = :product_id");
$stmt->execute(['product_id' => $productId]);
$responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'responses' => $responses]);
?>
