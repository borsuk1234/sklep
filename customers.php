<?php
session_start();
require_once 'db_connection.php';

if (!$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("SELECT id, username, email FROM users WHERE role = 'customer'");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'customers' => $customers]);
?>
