<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['is_admin']) {
    $name = $_POST['name'];
    $cost = $_POST['cost'];

    $stmt = $pdo->prepare("INSERT INTO delivery_methods (name, cost) VALUES (:name, :cost)");
    $stmt->execute(['name' => $name, 'cost' => $cost]);

    echo json_encode(['success' => true, 'message' => 'Metoda dostawy dodana pomyślnie.']);
}
?>
