<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<section>
    <div style="display: flex; flex-direction: column; gap: 1em; align-items: center;">
    <h2>Panel Administratora</h2>
    <p>Zalogowano jako: <?= htmlspecialchars($_SESSION['username']) ?></p>
    <nav style="display: flex; flex-direction: row; gap: 0.5em;">
        <a href="admin_products.php"><button class="nav-btn">Zarządzanie produktami</button></a>
        <a href="admin_categories.php"><button class="nav-btn">Zarządzanie kategoriami</button></a>
        <a href="admin_customers.php"><button class="nav-btn">Zarządzanie klientami</button></a>
        <a href="admin_orders.php"><button class="nav-btn">Zarządzanie zamówieniami</button></a>
    </nav>
    </div>
</section>
</body>
</html>
<?php
require 'footer.php';
?>
