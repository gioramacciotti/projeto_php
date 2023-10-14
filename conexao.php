<?php
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$db = 'projeto_php';

function conectar() {
    global $servidor, $usuario, $senha, $db;
    $conn = new mysqli($servidor, $usuario, $senha, $db);
    if ($conn->connect_error) {
        die("Erro na conexão com o MySQL: " . $conn->connect_error);
    }
    return $conn;
}
?>
