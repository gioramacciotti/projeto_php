<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Certifique-se de que o usuário está logado, caso contrário, redirecione para a página de login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

include('conexao.php');

if (isset($_POST['pedidoID'])) {
    $pedidoID = $_POST['pedidoID'];

    // Iniciar uma transação para garantir a exclusão segura de registros em ambas as tabelas
    mysqli_begin_transaction($con);

    // Exclua os itens do pedido da tabela itens_pedido
    $deleteItensQuery = "DELETE FROM itens_pedido WHERE id_pedido = $pedidoID";
    $deleteItensResult = mysqli_query($con, $deleteItensQuery);

    if (!$deleteItensResult) {
        // Ocorreu um erro ao excluir os itens do pedido, faça o rollback e saia
        mysqli_rollback($con);
        echo "Ocorreu um erro ao excluir os itens do pedido.";
        exit();
    }

    // Agora exclua o pedido da tabela pedidos
    $deletePedidoQuery = "DELETE FROM pedidos WHERE id = $pedidoID";
    $deletePedidoResult = mysqli_query($con, $deletePedidoQuery);

    if ($deletePedidoResult) {
        // Commit a transação, pois ambas as exclusões foram bem-sucedidas
        mysqli_commit($con);
        echo "Pedido e itens associados excluídos com sucesso!";
    } else {
        // Ocorreu um erro ao excluir o pedido, faça o rollback
        mysqli_rollback($con);
        echo "Ocorreu um erro ao excluir o pedido.";
    }
}
