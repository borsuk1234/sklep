<?php
require_once 'session.php';
require_once 'db_connection.php';
require_once 'cart.php'; 
require 'header.php';

// Włącz wyświetlanie błędów
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pobieramy dane o zamówieniu i metodzie płatności z sesji
$totalAmount = getCartTotal($pdo);
$paymentMethod = $_SESSION['payment_method'] ?? '';
$customerId = $_SESSION['customer_id'] ?? 0; // Zakładam, że customer_id jest przechowywane w sesji
$products = getCartItems($pdo); // Pobierz produkty z koszyka

// Zbieramy dane o użytkowniku z formularza
$userData = [
    'first_name' => $_POST['first_name'] ?? '',
    'last_name' => $_POST['last_name'] ?? '',
    'address' => $_POST['address'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'email' => $_POST['email'] ?? ''
];

$orderSuccess = false;
$errorMessage = '';

// Dodajemy zamówienie do bazy danych
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

    // Pobieramy ID ostatniego zamówienia
    $orderId = $pdo->lastInsertId();

    // Logowanie ID zamówienia
    error_log("Nowe zamówienie utworzone, ID: $orderId");

    // Usuwamy produkty z koszyka po złożeniu zamówienia na podstawie session_id
    $stmt = $pdo->prepare("DELETE FROM cart WHERE session_id = ?");
    $stmt->execute([session_id()]);

    // Potwierdzamy transakcję
    $pdo->commit();

    // Ustawiamy flagę sukcesu
    $orderSuccess = true;
} catch (Exception $e) {
    // Rzucenie błędu jeśli coś poszło nie tak
    $pdo->rollBack();

    // Logowanie szczegółów błędu
    error_log("Błąd podczas składania zamówienia: " . $e->getMessage());
    error_log("Śledzenie błędu: " . $e->getTraceAsString());

    // Komunikat dla użytkownika
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
