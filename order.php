<?php
require_once 'session.php';
require_once 'db_connection.php';
require_once 'cart.php'; 
require 'header.php';

$totalAmount = getCartTotal($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $_SESSION['payment_method'] = $_POST['payment_method'];
    $_SESSION['user_data'] = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'address' => $_POST['address'],
        'postal_code' => $_POST['postal_code'],
        'city' => $_POST['city'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email']
    ];
    header("Location: order_confirmation.php");
    exit;
}

$cartItems = getCartItems($pdo);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podsumowanie zamówienia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Podsumowanie zamówienia</h1>
        <div class="cart-summary">
            <h3>Twoje produkty:</h3>
            <ul>
                <?php if (!empty($cartItems)): ?>
                    <?php foreach ($cartItems as $item): ?>
                        <li><?= htmlspecialchars($item['name']) ?> - <?= number_format($item['price'], 2) ?> zł</li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Twój koszyk jest pusty.</li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="total-amount">
            <p>Kwota do zapłacenia: <?= number_format($totalAmount, 2) ?> zł</p>
        </div>
        <form method="POST">
            <div class="form-group">
                <label for="first_name">Imię:</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Nazwisko:</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>
            <div class="form-group">
                <label for="address">Adres:</label>
                <input type="text" name="address" id="address" required>
            </div>
            <div class="form-group">
                <label for="postal_code">Kod pocztowy:</label>
                <input type="text" name="postal_code" id="postal_code" required>
            </div>
            <div class="form-group">
                <label for="city">Miejscowość:</label>
                <input type="text" name="city" id="city" required>
            </div>
            <div class="form-group">
                <label for="phone">Numer telefonu:</label>
                <input type="tel" name="phone" id="phone" required>
            </div>
            <div class="form-group">
                <label for="email">Adres e-mail:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="payment-method">
                 <h3>Wybierz metodę płatności</h3>
                    <select name="payment_method" id="payment_method" class="payment-method-select">
                        <option value="card">Płatność kartą</option>
                        <option value="blik">Płatność BLIK</option>
                        <option value="paypal">Płatność PayPal</option>
                    </select>
            </div>

            <button type="submit" name="place_order" class="btn">Złóż zamówienie</button>
        </form>
    </div>

    <style>
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 32px;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"], input[type="tel"], input[type="email"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            color: #555;
        }

        p a {
            color: #007BFF;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .error-message, .success-message {
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }

        .payment-method {
            margin-top: 20px;
        }

        .payment-method label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
        }

        .payment-method-select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        .payment-method-select:focus {
            border-color: #007BFF;
            outline: none;
        }

        .payment-method-select option {
            padding: 10px;
        }
     </style>

</body>
</html>

<?php require 'footer.php'; ?>
