<?php
// config.php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'feira_tecnica'); // O nome do seu banco de dados

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Inicia a sessão em todas as páginas que incluírem este arquivo
session_start();
?>