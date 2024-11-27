<?php
require 'db_connection.php';
require 'header.php';

if (isset($_GET['delete'])) {
    $productId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    header("Location: admin_categories.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $categoryId = isset($_POST['id']) ? intval($_POST['id']) : null;

    if (!empty($name)) {
        if ($categoryId) {
            $stmt = $pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
            $stmt->execute(['name' => $name, 'id' => $categoryId]);
            echo "<p class='success-message'>Kategoria została zaktualizowana.</p>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
            echo "<p class='success-message'>Kategoria została dodana.</p>";
        }
    } else {
        echo "<p class='error-message'>Podaj nazwę kategorii.</p>";
    }
}

if (isset($_GET['delete'])) {
    $categoryId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute(['id' => $categoryId]);
    echo "<p class='success-message'>Kategoria została usunięta.</p>";
    header("Location: admin_categories.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie kategoriami</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<section>
    <h1>Zarządzanie kategoriami</h1>
    <form method="POST">
        <input type="hidden" name="id" id="category-id">
        <label>Nazwa kategorii: 
            <input type="text" name="name" id="category-name" required>
        </label>
        <button type="submit" id="form-submit">Dodaj kategorię</button>
    </form>

    <h2 style="margin-top: 20px;">Lista kategorii</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nazwa</th>
            <th>Akcje</th>
        </tr>
        <?php foreach ($categories as $category): ?>
        <tr>
            <td><?= $category['id'] ?></td>
            <td><?= htmlspecialchars($category['name']) ?></td>
            <td>
                <button onclick="editCategory(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name']) ?>')">Edytuj</button>
                <button style="background-color:red; border-radius: 0.25em;" href="admin_categories.php?delete=<?= $category['id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć tę kategorię?')">Usuń</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a style="margin-top:20px;"href="admin_panel.php"><button class="nav-btn">Powrót do panelu</button></a>
    </section>

<script>
    function editCategory(id, name) {
        document.getElementById('category-id').value = id;
        document.getElementById('category-name').value = name;

        document.getElementById('form-submit').textContent = 'Zaktualizuj kategorie';
    }
</script>

</body>
</html>
<?php require 'footer.php'; ?>
