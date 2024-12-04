<?php
session_start();

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $payment_method = $_POST['payment_method']; 

    if (empty($name) || empty($surname) || empty($address) || empty($phone) || empty($email)) {
        $error = "Wszystkie pola są wymagane!";
    } else {
        $order_id = saveOrder($name, $surname, $address, $phone, $email, $payment_method);

        header("Location: confirmation.php?order_id=$order_id");
        exit;
    }
}

function saveOrder($name, $surname, $address, $phone, $email, $payment_method) {
    $pdo = new PDO('mysql:host=localhost;dbname=sklep', 'root', '');

    $stmt = $pdo->prepare("INSERT INTO orders (name, surname, address, phone, email, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $surname, $address, $phone, $email, $payment_method]);

    return $pdo->lastInsertId();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Składanie zamówienia</title>
</head>
<body>
    <h2>Formularz zamówienia</h2>

    <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

    <form method="post">
        <label for="name">Imię:</label>
        <input type="text" name="name" id="name" required><br>

        <label for="surname">Nazwisko:</label>
        <input type="text" name="surname" id="surname" required><br>

        <label for="address">Adres:</label>
        <textarea name="address" id="address" required></textarea><br>

        <label for="phone">Numer telefonu:</label>
        <input type="tel" name="phone" id="phone" required><br>

        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="payment_method">Wybierz metodę płatności:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="credit_card">Karta kredytowa</option>
            <option value="paypal">PayPal</option>
            <option value="bank_transfer">Przelew bankowy</option>
        </select><br>

        <button type="submit">Potwierdź zamówienie</button>
    </form>
</body>
</html>
