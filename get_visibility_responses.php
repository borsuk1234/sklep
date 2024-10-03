<?php
require 'db_connection.php';

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    try {
        $stmt = $pdo->prepare('SELECT * FROM odpowiedzi AS o JOIN produkty AS p ON o.product_id = p.id WHERE o.product_id = :product_id AND p.is_visible = 1');
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($responses) {
            echo json_encode(['success' => true, 'responses' => $responses]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Brak odpowiedzi dla wybranego produktu.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Błąd zapytania do bazy danych: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nie podano ID produktu.']);
}
?>
