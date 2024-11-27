<?php
$host = 'mysql.ct8.pl';
$dbname = 'm50640_sklep';
$username = 'm50640_sklep';
$password = 'JacekSwider123@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
?>
