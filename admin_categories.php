<?php
require 'db_connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
        echo "Kategoria została dodana.";
    } else {
        echo "Podaj nazwę kategorii.";
    }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie kategoriami</title>
</head>
<body>
    <h1>Zarządzanie kategoriami</h1>
    <form method="POST">
        <label>Nazwa kategorii: <input type="text" name="name" required></label>
        <button type="submit">Dodaj kategorię</button>
    </form>
    <h2>Lista kategorii</h2>
    <ul>
        <?php foreach ($categories as $category): ?>
        <li><?= htmlspecialchars($category['name']) ?> <a href="delete_category.php?id=<?= $category['id'] ?>">Usuń</a></li>
        <?php endforeach; ?>
    </ul>
    <a href="admin_panel.php">Powrót do panelu</a>
</body>
</html>
