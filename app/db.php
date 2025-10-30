<?php
// app/db.php - MySQL connection (minimalist)
// Edit DB_USER and DB_PASS if necessary for your environment (XAMPP default: root, no password)
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'colecao');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    // If database doesn't exist yet, allow scripts like init_db.php to create it.
    // For normal usage, show a minimal message.
    die('Erro de conexão com o banco de dados: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
?>
