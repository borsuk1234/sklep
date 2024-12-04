<?php
session_start();

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    
    $pdo = new PDO('mysql:host=localhost;dbname=sklep', 'root', '');
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    if ($order) {
        echo "<h2>Potwierdzenie zamówienia</h2>";
        echo "<p>Twoje zamówienie zostało przyjęte. Szczegóły:</p>";
        echo "<p>ID zamówienia: " . $order['id'] . "</p>";
        echo "<p>Imię: " . $order['name'] . "</p>";
        echo "<p>Nazwisko: " . $order['surname'] . "</p>";
        echo "<p>Adres: " . $order['address'] . "</p>";
        echo "<p>Numer telefonu: " . $order['phone'] . "</p>";
        echo "<p>E-mail: " . $order['email'] . "</p>";
        echo "<p>Metoda płatności: " . $order['payment_method'] . "</p>";
        echo "<p>Dziękujemy za zakupy!</p>";
    } else {
        echo "<p>Nie znaleziono zamówienia.</p>";
    }
} else {
    echo "<p>Brak ID zamówienia.</p>";
}
?>
