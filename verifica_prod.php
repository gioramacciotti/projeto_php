<?php
include('conexao.php');

if (isset($_POST['produtoID'])) {
    $produtoID = $_POST['produtoID'];

    $query = "SELECT valor_unitario, unidade_medida FROM produtos WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $produtoID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $valorUnitario, $unidadeMedida);

        if (mysqli_stmt_fetch($stmt)) {
            $response = array('valor_unitario' => $valorUnitario, 'unidade_medida' => $unidadeMedida);
            echo json_encode($response);
        } else {
            echo 'Produto não encontrado';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo 'Erro na consulta ao banco de dados.';
    }
}
?>
