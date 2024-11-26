<?php
require_once 'db_connection.php';
require 'header.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Brak dostępu.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name']);
    $description = sanitize_input($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $categories = $_POST['categories'] ?? [];
    $parameters = $_POST['parameters'] ?? [];
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        $image = $imagePath;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (:name, :description, :price, :stock, :image)");
        $stmt->execute(['name' => $name, 'description' => $description, 'price' => $price, 'stock' => $stock, 'image' => $image]);
        $productId = $pdo->lastInsertId();

        foreach ($categories as $categoryId) {
            $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)");
            $stmt->execute(['product_id' => $productId, 'category_id' => $categoryId]);
        }

        foreach ($parameters as $paramId => $value) {
            $stmt = $pdo->prepare("INSERT INTO product_parameters (product_id, parameter_id, value) VALUES (:product_id, :parameter_id, :value)");
            $stmt->execute(['product_id' => $productId, 'parameter_id' => $paramId, 'value' => sanitize_input($value)]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Produkt został dodany.']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Błąd dodawania produktu: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe żądanie.']);
}
require 'footer.php';
?>
