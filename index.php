<?php
require_once 'db_connection.php';

$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : null;
if ($categoryId) {
    $stmt = $pdo->prepare("
        SELECT * FROM products 
        WHERE id IN (
            SELECT product_id 
            FROM product_categories 
            WHERE category_id = :category_id
        )
    ");
    $stmt->execute(['category_id' => $categoryId]);
} else {
    $stmt = $pdo->query("SELECT * FROM products");
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep z zegarkami</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php

require 'header.php';
?>
    <main >
        <div style="width: 100%; display: flex; flex-direction: column; gap: 0.5em; padding: 45px 15px; background-color: white; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); margin-bottom: 4em;">
            <h1 style="margin: 0; text-align: center;">Kategorie</h1>
            <div style="display: flex; flex-direction: row; gap: 1em;">
                <a href="index.php">
                    <button class="btn">Wszystkie produkty
                    </button>
                </a>
                <?php foreach ($categories as $category): ?>
                    <a href="index.php?category_id=<?= htmlspecialchars($category['id']) ?>">
                        <button class="btn">
                            <?= htmlspecialchars($category['name']) ?>
                        </button>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div style="width: 100%; display: flex; flex-direction: column; gap: 1em; padding: 15px; background-color: white; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
            <h2>Produkty</h2>
            <?php if (!empty($products)): ?>
                <div class="product-list">
                    <?php foreach ($products as $product): ?>
                        <div class="product-item">
                            <img 
                                src="<?= htmlspecialchars($product['image']) ?>" 
                                alt="<?= htmlspecialchars($product['name']) ?>" 
                                class="product-image">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <p><strong><?= number_format($product['price'], 2) ?> zł</strong></p>
                            <a href="product_details.php?id=<?= $product['id'] ?>" class="btn">Szczegóły</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Brak produktów w tej kategorii.</p>
            <?php endif; ?>
            </div>
    </main>

    <?php

require 'footer.php';
?>
</body>
</html>
