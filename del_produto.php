<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

include('conexao.php');

if (isset($_POST['produtoID'])) {
    $produtoID = $_POST['produtoID'];

    // Exclua o produto da tabela produtos
    $deleteProdutoQuery = "DELETE FROM produtos WHERE id = $produtoID";
    $deleteProdutoResult = mysqli_query($con, $deleteProdutoQuery);

    if ($deleteProdutoResult) {
        echo "Produto excluído com sucesso!";
    } else {
        echo "Ocorreu um erro ao excluir o produto.";
    }
}
?>
