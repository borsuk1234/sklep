<?php
$customers = $pdo->query("SELECT * FROM customers")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><title>Klienci</title></head>
<body>
    <h1>Zarządzanie klientami</h1>
    <table>
        <tr>
            <th>Imię</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Notatki</th>
            <th>Akcja</th>
        </tr>
        <?php foreach ($customers as $customer): ?>
        <tr>
            <td><?= htmlspecialchars($customer['name']) ?></td>
            <td><?= htmlspecialchars($customer['email']) ?></td>
            <td><?= htmlspecialchars($customer['phone']) ?></td>
            <td><?= htmlspecialchars($customer['notes']) ?></td>
            <td>
                <a href="edit_customer.php?id=<?= $customer['id'] ?>">Edytuj</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
