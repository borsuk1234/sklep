<?php
require 'header.php';
?>
<section>
    <h2>Panel Administratora</h2>
    <p>Zalogowano jako: <?= htmlspecialchars($_SESSION['username']) ?></p>
    <nav>
        <ul>
            <li><a href="admin_products.php">Zarządzanie produktami</a></li>
            <li><a href="admin_orders.php">Zarządzanie zamówieniami</a></li>
        </ul>
    </nav>
</section>
<?php
require 'footer.php';
?>