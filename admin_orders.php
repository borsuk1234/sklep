<?php
require 'db_connection.php';
require 'header.php';

if (!(isset($_SESSION['is_admin']) && $_SESSION['is_admin'])) {
    header('Location: login.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
   
    $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :order_id");
    $stmt->bindParam(':status', $_POST['status'], PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $_POST['order_id'], PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo '<p>Status zamówienia zaktualizowany pomyślnie!</p>';
    } else {
        echo '<p>Błąd aktualizacji statusu zamówienia.</p>';
    }
}


$stmt = $pdo->prepare("SELECT * FROM orders");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie zamówieniami</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Zamówienia</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Klient</th>
            <th>Imie</th>
            <th>Nazwisko</th>
            <th>Adres</th>
            <th>Telefon</th>
            <th>Produkty</th>
            <th>Kwota do zapłaty</th>
            <th>Status</th>
            <th>Akcja</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= htmlspecialchars($order['id']) ?></td>
            <td><?= htmlspecialchars($order['customer_id']) ?></td>
            <td><?= htmlspecialchars($order['first_name']) ?></td>
            <td><?= htmlspecialchars($order['last_name']) ?></td>
            <td><?= htmlspecialchars($order['address']) ?></td>
            <td><?= htmlspecialchars($order['phone']) ?></td>
            <td><?= htmlspecialchars($order['products']) ?></td>
            <td><?= htmlspecialchars($order['total_price']) ?></td>
            <td><?= htmlspecialchars($order['status']) ?></td>
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                    <select name="status">
                        <option value="new" <?= $order['status'] == 'new' ? 'selected' : '' ?>>Nowe</option>
                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>W realizacji</option>
                        <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Zrealizowane</option>
                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Anulowane</option>
                    </select>
                    <button type="submit">Zaktualizuj</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
