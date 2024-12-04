<?php
require_once 'session.php';
require_once 'db_connection.php';
require_once 'cart.php'; 
require 'header.php';

$totalAmount = getCartTotal($pdo);

$paymentMethod = $_SESSION['payment_method'] ?? '';

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potwierdzenie zamówienia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Potwierdzenie zamówienia</h1>

        <p>Kwota do zapłacenia: <?= number_format($totalAmount, 2) ?> zł</p>

        <?php if ($paymentMethod == 'card'): ?>
            <h2>Płatność kartą kredytową</h2>
            <form method="POST" action="order_success.php">
                <div class="form-group">
                    <label for="card_number">Numer karty:</label>
                    <input type="text" name="card_number" id="card_number" required>
                </div>
                <div class="form-group">
                    <label for="expiry_date">Data ważności:</label>
                    <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="text" name="cvv" id="cvv" required>
                </div>
                <button type="submit" class="btn">Zatwierdź płatność</button>
            </form>

        <?php elseif ($paymentMethod == 'blik'): ?>
            <h2>Płatność BLIK</h2>
            <form method="POST" action="order_success.php">
                <div class="form-group">
                    <label for="blik_code">Kod BLIK:</label>
                    <input type="text" name="blik_code" id="blik_code" required>
                </div>
                <button type="submit" class="btn">Zatwierdź płatność</button>
            </form>

        <?php elseif ($paymentMethod == 'paypal'): ?>
            <h2>Płatność PayPal</h2>
            <form method="POST" action="order_success.php">
                <div class="form-group">
                    <label for="paypal_email">Adres e-mail PayPal:</label>
                    <input type="email" name="paypal_email" id="paypal_email" required>
                </div>
                <button type="submit" class="btn">Zatwierdź płatność</button>
            </form>

        <?php else: ?>
            <p>Nie wybrano metody płatności. Proszę wrócić do poprzedniego kroku.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php require 'footer.php'; ?>
