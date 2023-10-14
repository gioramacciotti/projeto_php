<?php
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nome = $_POST["nome"];
$data_nasc = $_POST["data_nasc"];
$endereco = $_POST["endereco"];
$numero = $_POST["numero"];
$bairro = $_POST["bairro"];
$cidade = $_POST["cidade"];
$estado = $_POST["estado"];
$email = $_POST["email"];
$cpf_cnpj = $_POST["cpf_cnpj"];
$rg = $_POST["rg"];
$telefone = $_POST["telefone"];
$celular = $_POST["celular"];

$login = $_POST["login"];
$senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include('conexao.php');
mysqli_begin_transaction($con) or die(mysqli_connect_error());

try {
    // Inserir o cliente na tabela de clientes
    $query = "INSERT INTO clientes (nome, data_nasc, endereco, numero, bairro, cidade, estado, email, cpf_cnpj, rg, telefone, celular)
        VALUES ('$nome', '$data_nasc', '$endereco', '$numero', '$bairro', '$cidade', '$estado', '$email', '$cpf_cnpj', '$rg', '$telefone', '$celular')";
    $resu = mysqli_query($con, $query);

    // Recuperar o ID do cliente inserido
    $id_cliente = mysqli_insert_id($con);

    // Inserir o usuário na tabela de login_usuarios
    $query_usuario = "INSERT INTO login_usuarios (login, senha, id_cliente) VALUES ('$login', '$senha', '$id_cliente')";
    $resu_usuario = mysqli_query($con, $query_usuario);

    mysqli_commit($con);
    $_SESSION['msg'] = "<p style='color:green; text-align:center;'><b>Usuário cadastrado com sucesso</b></p>";
    header("Location: cad_usuario.php");
    
} catch (mysqli_sql_exception $exception) {
    mysqli_rollback($con);

    $_SESSION['msg'] = "<p style='color:red; text-align:center;'><b>O usuário não foi cadastrado, verifique</b></p>";
    header("Location: cad_usuario.php");  
    throw $exception;
}
mysqli_close($con);
?>
