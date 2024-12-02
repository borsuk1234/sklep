<?php
header('Content-Type: application/json');
require 'db_connection.php';

require 'db_connection.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Błąd połączenia z bazą danych: ' . $e->getMessage()]);
    exit();
}

$response = ['success' => false];

if (isset($_POST['product']) && isset($_POST['question1']) && isset($_POST['question2'])) {
    $productId = $_POST['product'];
    $question1 = $_POST['question1'];
    $question2 = $_POST['question2'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM odpowiedzi WHERE ip_address = :ip_address AND product_id = :product_id");
    $stmt->execute(['ip_address' => $ip_address, 'product_id' => $productId]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO odpowiedzi (product_id, question1, question2, ip_address) VALUES (:product_id, :question1, :question2, :ip_address)");
        if ($stmt->execute(['product_id' => $productId, 'question1' => $question1, 'question2' => $question2, 'ip_address' => $ip_address])) {
            $response['success'] = true;
        }
    } else {
        $response['message'] = 'Już oceniłeś ten produkt.';
    }
}

echo json_encode($response);
?>
