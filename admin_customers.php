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
$customers = $pdo->query("SELECT * FROM customers")->fetchAll(PDO::FETCH_ASSOC);

$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer-edit'])) {
    $customerId = intval($_POST['id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $notes = trim($_POST['notes']);

    if ($customerId > 0) {
        $stmt = $pdo->prepare("UPDATE customers SET name = :name, email = :email, phone = :phone, notes = :notes WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'notes' => $notes,
            'id' => $customerId
        ]);
        echo "<p class='success-message'>Dane klienta zostały zaktualizowane.</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user-edit'])) {
    $userId = intval($_POST['id']);
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);

    if ($userId > 0) {
        $stmt = $pdo->prepare("UPDATE users SET username = :username, role = :role WHERE id = :id");
        $stmt->execute([
            'username' => $username,
            'role' => $role,
            'id' => $userId
        ]);
        echo "<p class='success-message'>Dane użytkownika zostały zaktualizowane.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie klientami i użytkownikami</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<section>
    <h1>Zarządzanie klientami i użytkownikami</h1>

    <h2>Klienci</h2>
    <form method="POST" id="customer-form">
        <input type="hidden" name="id" id="customer-id">
        <label>Imię i nazwisko:
            <input type="text" name="name" id="customer-name" required>
        </label>
        <label>Email:
            <input type="email" name="email" id="customer-email" required>
        </label>
        <label>Telefon:
            <input type="text" name="phone" id="customer-phone">
        </label>
        <label>Notatki:
            <textarea style="width: 200px; height: 30px; resize: none;" name="notes" id="customer-notes"></textarea>
        </label>
        <button type="submit" name="customer-edit" id="form-submit">Zaktualizuj klienta</button>
    </form>

    <table style="margin-top:20px;" border="1">
        <tr>
            <th>ID</th>
            <th>Imię i nazwisko</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Notatki</th>
            <th>Akcje</th>
        </tr>
        <?php foreach ($customers as $customer): ?>
        <tr>
            <td><?= $customer['id'] ?></td>
            <td><?= htmlspecialchars($customer['name']) ?></td>
            <td><?= htmlspecialchars($customer['email']) ?></td>
            <td><?= htmlspecialchars($customer['phone']) ?></td>
            <td><?= htmlspecialchars($customer['notes']) ?></td>
            <td>
                <button onclick="editCustomer(
                    <?= $customer['id'] ?>,
                    '<?= htmlspecialchars($customer['name'], ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($customer['email'], ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($customer['phone'], ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($customer['notes'], ENT_QUOTES) ?>'
                )">Edytuj</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2 style="margin-top:20px;">Użytkownicy</h2>
    <form method="POST" id="user-form">
        <input type="hidden" name="id" id="user-id">
        <label>Nazwa użytkownika:
            <input type="text" name="username" id="user-username" required>
        </label>
        <label>Rola:
            <select name="role" id="user-role">
                <option value="user">Użytkownik</option>
                <option value="admin">Administrator</option>
            </select>
        </label>
        <button type="submit" name="user-edit" id="user-submit">Zaktualizuj użytkownika</button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nazwa użytkownika</th>
            <th>Rola</th>
            <th>Akcje</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <button onclick="editUser(
                    <?= $user['id'] ?>,
                    '<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($user['role'], ENT_QUOTES) ?>'
                )">Edytuj</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="admin_panel.php"><button style="margin-top: 20px;" class="nav-btn">Powrót do panelu</button></a>
</section>

<script>
    function editCustomer(id, name, email, phone, notes) {
        document.getElementById('customer-id').value = id;
        document.getElementById('customer-name').value = name;
        document.getElementById('customer-email').value = email;
        document.getElementById('customer-phone').value = phone;
        document.getElementById('customer-notes').value = notes;

        document.getElementById('form-submit').textContent = 'Zaktualizuj klienta';
    }

    function editUser(id, username, role) {
        document.getElementById('user-id').value = id;
        document.getElementById('user-username').value = username;
        document.getElementById('user-role').value = role;

        document.getElementById('user-submit').textContent = 'Zaktualizuj użytkownika';
    }
</script>
</body>
</html>
<?php require 'footer.php'; ?>
