<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'bd_system';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

function sanitize($conn, $data) {
    return $conn->real_escape_string($data);
}
?>
