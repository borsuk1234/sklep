<?php
require 'db_connection.php';
require 'session.php';
session_unset();
session_destroy();
header('Location: index.php');
exit();
?>
