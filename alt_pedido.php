<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('conexao.php');

$id_pedido = $_POST["id_pedido"];
$id_cliente = $_POST["cliente_id"];
$data = $_POST["data"];
$observacao = $_POST["observacao"];
$cond_pagto = $_POST["cond_pagto"];
$prazo_entrega = $_POST["prazo_entrega"];

// Iniciar uma transação para garantir a integridade dos dados
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
mysqli_begin_transaction($con) or die(mysqli_connect_error());

try {
    // Atualizar os dados do pedido na tabela pedidos
    $queryPedido = "UPDATE pedidos SET data = ?, id_cliente = ?, observacao = ?, cond_pagto = ?, prazo_entrega = ? WHERE id = ?";
    $stmtPedido = mysqli_prepare($con, $queryPedido);
    mysqli_stmt_bind_param($stmtPedido, "sisssd", $data, $id_cliente, $observacao, $cond_pagto, $prazo_entrega, $id_pedido);
    mysqli_stmt_execute($stmtPedido);

    // Deletar os itens antigos do pedido da tabela itens_pedido
    $queryDeleteItens = "DELETE FROM itens_pedido WHERE id_pedido = ?";
    $stmtDeleteItens = mysqli_prepare($con, $queryDeleteItens);
    mysqli_stmt_bind_param($stmtDeleteItens, "d", $id_pedido);
    mysqli_stmt_execute($stmtDeleteItens);

    // Inserir os novos itens do pedido na tabela itens_pedido
    if (isset($_POST['produto_id']) && isset($_POST['quantidade'])) {
        $produto_ids = $_POST['produto_id'];
        $quantidades = $_POST['quantidade'];

        $queryItem = "INSERT INTO itens_pedido (id_pedido, id_produto, qtde) VALUES (?, ?, ?)";
        $stmtItem = mysqli_prepare($con, $queryItem);

        for ($i = 0; $i < count($produto_ids); $i++) {
            $produto_id = $produto_ids[$i];
            $quantidade = $quantidades[$i];

            mysqli_stmt_bind_param($stmtItem, "iii", $id_pedido, $produto_id, $quantidade);
            mysqli_stmt_execute($stmtItem);
        }
    }

    // Confirmar a transação
    mysqli_commit($con);

    $_SESSION['msg'] = "<p style='color: green; text-align: center;'><b>Pedido atualizado com sucesso</b></p>";
    header("Location: cad_pedido.php?id=$id_pedido");
} catch (mysqli_sql_exception $exception) {
    mysqli_rollback($con);

    $_SESSION['msg'] = "<p style='color: red; text-align: center;'><b>Não foi possível atualizar o pedido, verifique</b></p>";
    header("Location: cad_pedido.php?id=$id_pedido");
    throw $exception;
}

mysqli_close($con);
?>
