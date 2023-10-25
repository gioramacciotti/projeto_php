<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('conexao.php');

$id = $_POST["id"];
$nome = mysqli_real_escape_string($con, $_POST["nome"]);
$qtde_estoque = $_POST["qtde_estoque"];
$valor_unitario = $_POST["valor_unitario"];
$unidade_medida = mysqli_real_escape_string($con, $_POST["unidade_medida"]);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_begin_transaction($con) or die(mysqli_connect_error());

try {
    // Use declarações preparadas para evitar injeção de SQL
    $queryProduto = "UPDATE produtos SET nome = ?, qtde_estoque = ?, valor_unitario = ?, unidade_medida = ? WHERE id = ?";
    $stmtProduto = mysqli_prepare($con, $queryProduto);
    mysqli_stmt_bind_param($stmtProduto, "sddsd", $nome, $qtde_estoque, $valor_unitario, $unidade_medida, $id);
    mysqli_stmt_execute($stmtProduto);

    mysqli_commit($con);
    $_SESSION['msg'] = "<p style='color: green; text-align: center;'><b>Dados do produto atualizados com sucesso</b></p>";
    header("Location: cad_produto.php?id=$id");
} catch (mysqli_sql_exception $exception) {
    mysqli_rollback($con);

    $_SESSION['msg'] = "<p style='color: red; text-align: center;'><b>Não foi possível atualizar os dados do produto, verifique</b></p>";
    header("Location: cad_produto.php?id=$id");
    throw $exception;
}
mysqli_close($con);
?>
