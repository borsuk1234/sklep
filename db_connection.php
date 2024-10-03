<?php
$host = 's1.ct8.pl';
$dbname = 'm44406_oceny_produktow';
$username = 'm44406_bambo';
$password = 'Jacekswider!@3';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Błąd połączenia z bazą danych: ' . $e->getMessage()]);
    exit();
}
?>
