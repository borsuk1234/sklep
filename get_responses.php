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

$productId = isset($_GET['product_id']) ? $_GET['product_id'] : null;
$isAdmin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : false;

if ($productId) {
    if ($isAdmin) {
        $stmt = $pdo->prepare("SELECT * FROM odpowiedzi WHERE product_id = :product_id");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM odpowiedzi WHERE product_id = :product_id AND (SELECT is_visible FROM produkty WHERE id = :product_id) = 1");
    }
    $stmt->execute(['product_id' => $productId]);
    $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'responses' => $responses]);
} else {
    echo json_encode(['success' => false, 'message' => 'Brak podanego identyfikatora produktu.']);
}
?>
