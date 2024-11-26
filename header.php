<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep z zegarkami</title>
    <link rel="stylesheet" href="styles.css"> <!-- Plik CSS -->
</head>
<body>
<header>
    <h1>Sklep z zegarkami</h1>
    <nav>
        <ul>
            <li><a href="index.php">Strona główna</a></li>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <li><a href="admin_panel.php">Panel Administratora</a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="logout.php">Wyloguj się</a></li>
            <?php else: ?>
                <li><a href="login.php">Zaloguj się</a></li>
                <li><a href="register.php">Rejestracja</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
