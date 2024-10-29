<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['is_admin']) {
    $name = $_POST['product_name'];
    $categories = $_POST['categories'];
    $parameters = $_POST['parameters'];

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO produkty (nazwa) VALUES (:name)");
        $stmt->execute(['name' => $name]);
        $productId = $pdo->lastInsertId();

        // Assign categories
        $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)");
        foreach ($categories as $categoryId) {
            $stmt->execute(['product_id' => $productId, 'category_id' => $categoryId]);
        }

        // Add parameters
        $stmt = $pdo->prepare("INSERT INTO product_parameters (product_id, parameter_name, parameter_value) VALUES (:product_id, :name, :value)");
        foreach ($parameters as $name => $value) {
            $stmt->execute(['product_id' => $productId, 'name' => $name, 'value' => $value]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Produkt dodany pomyślnie.']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Błąd dodawania produktu: ' . $e->getMessage()]);
    }
}
?>
