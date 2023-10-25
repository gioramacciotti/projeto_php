<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

include('conexao.php');

if (isset($_POST['clienteID'])) {
    $clienteID = $_POST['clienteID'];

    // Exclua o cliente da tabela login_usuarios
    $deleteLoginQuery = "DELETE FROM login_usuarios WHERE id_cliente = $clienteID";
    $deleteLoginResult = mysqli_query($con, $deleteLoginQuery);

    // Exclua o cliente da tabela clientes
    $deleteClienteQuery = "DELETE FROM clientes WHERE id = $clienteID";
    $deleteClienteResult = mysqli_query($con, $deleteClienteQuery);

    if ($deleteLoginResult && $deleteClienteResult) {
        echo "Cliente excluído com sucesso!";
    } else {
        echo "Ocorreu um erro ao excluir o cliente.";
    }
}
?>
