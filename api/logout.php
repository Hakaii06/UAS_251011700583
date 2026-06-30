<?php
session_start();
session_destroy(); // Hancurkan semua session data login
header("Location: login"); // Tendang ke /login tanpa .php
exit;
?>
