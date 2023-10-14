<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('conexao.php');

$login = $_POST["login"];
$senha = $_POST["senha"];
$senha = password_hash($senha, PASSWORD_DEFAULT);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Inserir o usuário na tabela de login_usuarios
    $query_usuario = "INSERT INTO login_usuarios (login, senha) VALUES ('$login', '$senha')";
    $resu_usuario = mysqli_query($con, $query_usuario);

    $_SESSION['msg'] = "<p style='color:green; text-align:center;'><b>Usuário cadastrado com sucesso</b></p>";
    header("Location: cad_usuario.php");
    
} catch (mysqli_sql_exception $exception) {
    $_SESSION['msg'] = "<p style='color:red; text-align:center;'><b>O usuário não foi cadastrado, verifique</b></p>";
    header("Location: cad_usuario.php");  
    throw $exception;
}
mysqli_close($con);
?>
