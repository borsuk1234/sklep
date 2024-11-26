<?php
session_start(); // Rozpocznij lub kontynuuj sesję

// Wyczyść dane sesji
session_unset();
session_destroy();

// Przekierowanie na stronę główną
header('Location: index.php');
exit();
?>
