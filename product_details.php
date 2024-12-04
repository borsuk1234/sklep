<?php
require_once 'db_connection.php';
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: index.php');
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT c.name 
        FROM categories c
        JOIN product_categories pc ON c.id = pc.category_id
        WHERE pc.product_id = :product_id
    ");
    $stmt->execute(['product_id' => $productId]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detal produktu - <?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require 'header.php'; ?>

    <main>
        <div style="padding: 45px 15px; background-color: white; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); margin-bottom: 4em;">
            <h1 style="text-align: center;">Szczegóły produktu</h1>
            <div style="display: flex; flex-direction: row; gap: 2em; justify-content: center; align-items: center;">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image" style="max-width: 300px;">
                <div>
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <p><strong>Cena:</strong> <?= number_format($product['price'], 2) ?> zł</p>
                    <p><strong>Opis:</strong> <?= nl2br(htmlspecialchars($product['description'])) ?></p>

                    <h3>Kategorie:</h3>
                    <ul>
                        <?php foreach ($categories as $category): ?>
                            <li><?= htmlspecialchars($category['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <a href="index.php" class="btn">Powrót do sklepu</a>
                </div>
            </div>
        </div>
    </main>

    <?php require 'footer.php'; ?>
</body>
</html>
