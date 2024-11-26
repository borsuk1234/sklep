<?php
$orders = $pdo->query("SELECT * FROM orders")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><title>Zarządzanie zamówieniami</title></head>
<body>
    <h1>Zamówienia</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Klient</th>
            <th>Produkty</th>
            <th>Status</th>
            <th>Akcja</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= $order['customer_id'] ?></td>
            <td><?= $order['products'] ?></td>
            <td><?= $order['status'] ?></td>
            <td>
                <form method="POST" action="update_order.php">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <select name="status">
                        <option value="new">Nowe</option>
                        <option value="processing">W realizacji</option>
                        <option value="completed">Zrealizowane</option>
                        <option value="cancelled">Anulowane</option>
                    </select>
                    <button type="submit">Zaktualizuj</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
