<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('conexao.php');

$id = $_POST["id"];
$nome = mysqli_real_escape_string($con, $_POST["nome"]);
$data_nasc = $_POST["data_nasc"];
$endereco = mysqli_real_escape_string($con, $_POST["endereco"]);
$numero = $_POST["numero"];
$bairro = mysqli_real_escape_string($con, $_POST["bairro"]);
$cidade = mysqli_real_escape_string($con, $_POST["cidade"]);
$estado = $_POST["estado"];
$email = $_POST["email"];
$cpf_cnpj = $_POST["cpf_cnpj"];
$rg = $_POST["rg"];
$telefone = $_POST["telefone"];
$celular = $_POST["celular"];
$login = mysqli_real_escape_string($con, $_POST["login"]);
$senha = $_POST["senha"];

// Use password_hash para armazenar senhas com segurança
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_begin_transaction($con) or die(mysqli_connect_error());

try {
    // Use declarações preparadas para evitar injeção de SQL
    $queryCliente = "UPDATE clientes SET nome = ?, data_nasc = ?, endereco = ?, numero = ?, bairro = ?, cidade = ?, estado = ?, email = ?, cpf_cnpj = ?, rg = ?, telefone = ?, celular = ? WHERE id = ?";
    $stmtCliente = mysqli_prepare($con, $queryCliente);
    mysqli_stmt_bind_param($stmtCliente, "ssssssssssssd", $nome, $data_nasc, $endereco, $numero, $bairro, $cidade, $estado, $email, $cpf_cnpj, $rg, $telefone, $celular, $id);
    mysqli_stmt_execute($stmtCliente);

    $queryUsuario = "UPDATE login_usuarios SET login = ?, senha = ? WHERE id_cliente = ?";
    $stmtUsuario = mysqli_prepare($con, $queryUsuario);
    mysqli_stmt_bind_param($stmtUsuario, "ssd", $login, $senhaHash, $id);
    mysqli_stmt_execute($stmtUsuario);

    mysqli_commit($con);
    $_SESSION['msg'] = "<p style='color: green; text-align: center;'><b>Dados do usuário atualizados com sucesso</b></p>";
    header("Location: cad_cliente.php?id=$id");
} catch (mysqli_sql_exception $exception) {
    mysqli_rollback($con);

    $_SESSION['msg'] = "<p style='color: red; text-align: center;'><b>Não foi possível atualizar os dados do usuário, verifique</b></p>";
    header("Location: cad_cliente.php?id=$id");
    throw $exception;
}
mysqli_close($con);
?>
