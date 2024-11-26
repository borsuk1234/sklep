<?php
require 'db_connection.php';
require 'header.php';

// Usuwanie produktu
if (isset($_GET['delete'])) {
    $productId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    header("Location: admin_products.php");
    exit;
}

// Pobieranie listy produktów
$products = $pdo->query("SELECT p.id, p.name, p.price, GROUP_CONCAT(c.name SEPARATOR ', ') as categories
                         FROM products p
                         LEFT JOIN product_categories pc ON p.id = pc.product_id
                         LEFT JOIN categories c ON pc.category_id = c.id
                         GROUP BY p.id")->fetchAll(PDO::FETCH_ASSOC);

// Pobieranie kategorii (do edycji i dodawania)
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Obsługa formularza dodawania/edycji produktu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $selectedCategories = $_POST['categories'] ?? [];

    if (!empty($name) && $price > 0) {
        $pdo->beginTransaction();
        try {
            if ($productId) {
                // Edycja istniejącego produktu
                $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id");
                $stmt->execute(['name' => $name, 'description' => $description, 'price' => $price, 'id' => $productId]);

                // Aktualizacja kategorii
                $stmt = $pdo->prepare("DELETE FROM product_categories WHERE product_id = :product_id");
                $stmt->execute(['product_id' => $productId]);

                foreach ($selectedCategories as $categoryId) {
                    $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)");
                    $stmt->execute(['product_id' => $productId, 'category_id' => $categoryId]);
                }
            } else {
                // Dodawanie nowego produktu
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price) VALUES (:name, :description, :price)");
                $stmt->execute(['name' => $name, 'description' => $description, 'price' => $price]);
                $newProductId = $pdo->lastInsertId();

                foreach ($selectedCategories as $categoryId) {
                    $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)");
                    $stmt->execute(['product_id' => $newProductId, 'category_id' => $categoryId]);
                }
            }

            $pdo->commit();
            header("Location: admin_products.php");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Błąd: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie produktami</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Zarządzanie produktami</h1>

    <!-- Formularz dodawania/edycji produktu -->
    <form method="POST">
        <input type="hidden" name="product_id" id="product_id">
        <label>Nazwa produktu: <input type="text" name="name" id="name" required></label><br>
        <label>Opis produktu: <textarea name="description" id="description" required></textarea></label><br>
        <label>Cena: <input type="number" step="0.01" name="price" id="price" required></label><br>
        <label>Kategorie:</label><br>
        <?php foreach ($categories as $category): ?>
            <label>
                <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>" class="category-checkbox">
                <?= htmlspecialchars($category['name']) ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit">Zapisz</button>
    </form>

    <h2>Lista produktów</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Cena</th>
            <th>Kategorie</th>
            <th>Akcje</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= number_format($product['price'], 2) ?> PLN</td>
            <td><?= htmlspecialchars($product['categories']) ?></td>
            <td>
                <button onclick="editProduct(<?= $product['id'] ?>, '<?= htmlspecialchars($product['name']) ?>', '<?= htmlspecialchars($product['description']) ?>', <?= $product['price'] ?>, '<?= $product['categories'] ?>')">Edytuj</button>
                <a href="admin_products.php?delete=<?= $product['id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć ten produkt?')">Usuń</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="admin_panel.php">Powrót do panelu</a>

    <script>
        function editProduct(id, name, description, price, categories) {
            document.getElementById('product_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('description').value = description;
            document.getElementById('price').value = price;

            // Resetuj wszystkie checkboxy kategorii
            document.querySelectorAll('.category-checkbox').forEach(checkbox => checkbox.checked = false);

            // Zaznacz odpowiednie checkboxy na podstawie kategorii produktu
            categories.split(', ').forEach(categoryName => {
                document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                    if (checkbox.nextSibling.textContent.trim() === categoryName) {
                        checkbox.checked = true;
                    }
                });
            });
        }
    </script>
</body>
</html>
<?php require 'footer.php'; ?>
