<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['is_admin']) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $slug = strtolower(str_replace(" ", "-", $title));

    $stmt = $pdo->prepare("INSERT INTO pages (title, content, slug) VALUES (:title, :content, :slug)");
    $stmt->execute(['title' => $title, 'content' => $content, 'slug' => $slug]);

    echo json_encode(['success' => true, 'message' => 'Strona dodana pomyślnie.']);
}
?>
