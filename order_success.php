<?php
require_once 'session.php';
require_once 'db_connection.php';
require_once 'cart.php'; 
require 'header.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_data']) || !isset($_SESSION['payment_method'])) {
    header("Location: order.php");
    exit;
}

$userData = $_SESSION['user_data'];
$paymentMethod = $_SESSION['payment_method'];
$totalAmount = getCartTotal($pdo);
$customerId = $_SESSION['customer_id'] ?? 0;
$products = getCartItems($pdo);

$orderSuccess = false;
$errorMessage = '';

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (first_name, last_name, address, phone, customer_id, products, total_price, payment_method, status, created_at) 
                       VALUES (:first_name, :last_name, :address, :phone, :customer_id, :products, :total_price, :payment_method, 'new', NOW())");

    $stmt->execute([
        ':first_name' => $userData['first_name'],
        ':last_name' => $userData['last_name'],
        ':address' => $userData['address'],
        ':phone' => $userData['phone'],
        ':customer_id' => $customerId,
        ':products' => json_encode($products),
        ':total_price' => $totalAmount,
        ':payment_method' => $paymentMethod
    ]);

    $orderId = $pdo->lastInsertId();

    error_log("Nowe zamówienie utworzone, ID: $orderId");
    $stmt = $pdo->prepare("DELETE FROM cart WHERE session_id = ?");
    $stmt->execute([session_id()]);

    $pdo->commit();

    $orderSuccess = true;
} catch (Exception $e) {
    $pdo->rollBack();

    error_log("Błąd podczas składania zamówienia: " . $e->getMessage());
    error_log("Śledzenie błędu: " . $e->getTraceAsString());

    $errorMessage = "Wystąpił błąd podczas składania zamówienia. Proszę spróbować ponownie.";
}
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

        <?php if ($orderSuccess): ?>
            <p>Zakup został dokonany pomyślnie. Kwota do zapłacenia: <?= number_format($totalAmount, 2) ?> zł</p>
            <p>Numer zamówienia: <?= $orderId ?></p>
        <?php else: ?>
            <p><?= $errorMessage ?></p>
        <?php endif; ?>
        
        <a href="index.php" class="btn">Powróć do strony głównej</a>
    </div>
</body>
</html>

<?php require 'footer.php'; ?>
