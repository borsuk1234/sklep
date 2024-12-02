<?php
require 'db_connection.php';
require 'header.php';

if (!(isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $productId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    header("Location: admin_products.php");
    exit;
}

$products = $pdo->query("SELECT p.id, p.name, p.description, p.price, p.image_path, GROUP_CONCAT(c.name SEPARATOR ', ') as categories
                         FROM products p
                         LEFT JOIN product_categories pc ON p.id = pc.product_id
                         LEFT JOIN categories c ON pc.category_id = c.id
                         GROUP BY p.id")->fetchAll(PDO::FETCH_ASSOC);

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $selectedCategories = $_POST['categories'] ?? [];
    $imagePath = null;

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowedTypes) && move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        } else {
            echo "Invalid file type or upload error.";
        }
    }

    if (!empty($name) && $price > 0) {
        $pdo->beginTransaction();
        try {
            if ($productId) {
                $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, price = :price" . 
                    ($imagePath ? ", image_path = :image_path" : "") . " WHERE id = :id");
                $params = ['name' => $name, 'description' => $description, 'price' => $price, 'id' => $productId];
                if ($imagePath) $params['image_path'] = $imagePath;
                $stmt->execute($params);

                $stmt = $pdo->prepare("DELETE FROM product_categories WHERE product_id = :product_id");
                $stmt->execute(['product_id' => $productId]);

                foreach ($selectedCategories as $categoryId) {
                    $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)");
                    $stmt->execute(['product_id' => $productId, 'category_id' => $categoryId]);
                }
            } else {
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_path) VALUES (:name, :description, :price, :image_path)");
                $stmt->execute(['name' => $name, 'description' => $description, 'price' => $price, 'image_path' => $imagePath]);
                $newProductId = $pdo->lastInsertId();

                foreach ($selectedCategories as $categoryId) {
                    $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)");
                    $stmt->execute(['product_id' => $newProductId, 'category_id' => $categoryId]);
                }
            }

            $pdo->commit();
            $_SESSION['success_message'] = "Produkt został poprawnie zapisany.";
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
    <div style="width: 100%; display: flex; flex-direction: column; gap: 2em; align-items: center;">
        <h1>Zarządzanie produktami</h1>

        <form method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 1em;">
            <div style="display: flex; flex-direction: row; gap: 1em;">
                <div style="display: flex; flex-direction: column; gap: 1em; border: 1px solid black; border-radius: 1em; padding: 1em;">
                    <input type="hidden" name="product_id" id="product_id">
                    <div>
                        <label>Nazwa produktu: </label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div>
                        <label>Opis produktu: </label>
                        <textarea name="description" id="description" required></textarea>
                    </div>
                    <div>
                        <label>Cena: </label>
                        <input type="number" step="0.01" name="price" id="price" required>
                    </div>
                    <div>
                        <label>Obrazek produktu: </label>
                        <input type="file" name="image" id="image">
                    </div>
                    <label>
                        <input type="checkbox" name="clear_image" id="clear_image"> Usuń obraz
                    </label>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.1em; border: 1px solid black; border-radius: 1em; padding: 1em;">
                    <label>Kategorie:</label><br>
                    <?php foreach ($categories as $category): ?>
                        <label>
                            <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>" class="category-checkbox">
                            <?= htmlspecialchars($category['name']) ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit">Zapisz</button>
        </form>

        <h2>Lista produktów</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Opis</th>
                <th>Cena</th>
                <th>Obrazek</th>
                <th>Kategorie</th>
                <th>Akcje</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= nl2br(htmlspecialchars($product['description'])) ?></td>
                <td><?= number_format($product['price'], 2) ?> PLN</td>
                <td>
                    <?php if ($product['image_path']): ?>
                        <img src="<?= $product['image_path'] ?>" alt="Product Image" width="100">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($product['categories']) ?></td>
                <td>
                    <button onclick="editProduct(<?= $product['id'] ?>)">Edytuj</button>
                    <a href="admin_products.php?delete=<?= $product['id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć ten produkt?')">Usuń</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <a href="admin_panel.php"><button>Powrót do panelu</button></a>
    </div>

    <script>
      function editProduct(id) {
        const productData = <?= json_encode($products); ?>;
        const productCategoriesData = <?= json_encode($categories); ?>;

        const product = productData.find(p => Number(p.id) === id);

        if (product) {
            document.getElementById('product_id').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('description').value = product.description;
            document.getElementById('price').value = product.price;

            document.querySelectorAll('.category-checkbox').forEach(checkbox => checkbox.checked = false);

            const productCategories = product.categories ? product.categories.split(', ') : [];
            productCategories.forEach(categoryName => {
                document.querySelectorAll('.category-checkbox').forEach(checkbox => {
                    const category = productCategoriesData.find(c => c.name === categoryName.trim());
                    if (category && checkbox.value == category.id) {
                        checkbox.checked = true;
                    }
                });
            });
        }
      }
    </script>
</body>
</html>

<?php require 'footer.php'; ?>
