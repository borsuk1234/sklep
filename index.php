<?php
require_once 'db_connection.php';


// Pobierz listę kategorii
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pobierz produkty na podstawie wybranej kategorii (jeśli podano)
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
    <link rel="stylesheet" href="styles.css"> <!-- Plik CSS -->
</head>
<body>
<?php

require 'header.php';
?>

    <main>
        <aside>
            <h2>Kategorie</h2>
            <ul>
                <li><a href="index.php">Wszystkie produkty</a></li>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="index.php?category_id=<?= htmlspecialchars($category['id']) ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
        
        <section>
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
        </section>
    </main>

    <?php

require 'footer.php';
?>
</body>
</html>
