<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

include('conexao.php');

$query = "SELECT p.id, p.data, c.nome AS nome_cliente, p.observacao, p.cond_pagto, p.prazo_entrega FROM pedidos p
          LEFT JOIN clientes c ON p.id_cliente = c.id";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Painel de Pedidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
        <div class="logo">
            <img src="logo.png" alt="Verzi Websystem" style="height: 40px;">
        </div>
        <div style="display: flex; align-items: center;">
            <a href="index.html" class="home-link">
                <i class="fas fa-home"></i>&nbsp;Início
            </a>
            <a href="logout.php" class="sign-out-link">
                <i class="fas fa-sign-out-alt"></i>&nbsp;Sair
            </a>
        </div>
    </header>

    <div class="container">
        <h1>
            <a href="index.html" class="back-link">
            <i class="fas fa-arrow-left back-icon" onclick="window.location='index.html';"></i>
            </a>
            Painel de Pedidos 
            <a href="cad_pedido.php" class="add-button">Incluir Pedido</a>
        </h1>
        
        <form method="post">
            <div class="filter-row">
                <div class="filter-field">
                    <label for="filter-date-start">Período inicial:</label>
                    <input type="date" name="filter-date-start" id="filter-date-start style="width: 10%">
                    </div>
                <div class="filter-field">
                    <label for="filter-date-final">Período final:</label>
                    <input type="date" name="filter-date-final" id="filter-date-final style="width: 10%">
                </div>
                <div class="filter-button">
                    <button type="submit" name="filter-submit" class="btn-filtro">Filtrar</button>
                </div>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID do Pedido</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Observação</th>
                    <th>Condição de Pagamento</th>
                    <th>Prazo de Entrega</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['filter-submit'])) {
                    $filterDateStart = $_POST['filter-date-start'];
                    $filterDateFinal = $_POST['filter-date-final'];
                
                    if (!empty($filterDateStart) && !empty($filterDateFinal)) {
                        $dateStart = date('Y-m-d', strtotime($filterDateStart));
                        $dateFinal = date('Y-m-d', strtotime($filterDateFinal));
                        $query = "SELECT p.id, p.data, c.nome AS nome_cliente, p.observacao, p.cond_pagto, p.prazo_entrega FROM pedidos p
                                  LEFT JOIN clientes c ON p.id_cliente = c.id
                                  WHERE p.data BETWEEN '$dateStart' AND '$dateFinal'";

                    } elseif (!empty($filterDateStart)) {
                        $dateStart = date('Y-m-d', strtotime($filterDateStart));
                        $query = "SELECT p.id, p.data, c.nome AS nome_cliente, p.observacao, p.cond_pagto, p.prazo_entrega FROM pedidos p
                                  LEFT JOIN clientes c ON p.id_cliente = c.id
                                  WHERE p.data >= '$dateStart'";

                    } elseif (!empty($filterDateFinal)) {
                        $dateFinal = date('Y-m-d', strtotime($filterDateFinal));
                        $query = "SELECT p.id, p.data, c.nome AS nome_cliente, p.observacao, p.cond_pagto, p.prazo_entrega FROM pedidos p
                                  LEFT JOIN clientes c ON p.id_cliente = c.id
                                  WHERE p.data <= '$dateFinal'";

                    } else {
                        $query = "SELECT p.id, p.data, c.nome AS nome_cliente, p.observacao, p.cond_pagto, p.prazo_entrega FROM pedidos p
                                  LEFT JOIN clientes c ON p.id_cliente = c.id";
                    }
                } else {
                    $query = "SELECT p.id, p.data, c.nome AS nome_cliente, p.observacao, p.cond_pagto, p.prazo_entrega FROM pedidos p
                              LEFT JOIN clientes c ON p.id_cliente = c.id";
                }
                

                $result = mysqli_query($con, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['data'])) . "</td>";
                    echo "<td>" . $row['nome_cliente'] . "</td>";
                    echo "<td>" . $row['observacao'] . "</td>";
                    echo "<td>" . $row['cond_pagto'] . "</td>";
                    echo "<td>" . $row['prazo_entrega'] . "</td>";
                    echo "<td>
                            <div class='btn-container'>
                                <a href='alt_pedido.php?id=" . $row['id'] . "' class='btn-alterar'>Editar</a>
                                <button class='btn-excluir' onclick='excluirPedido(" . $row['id'] . ")'>Excluir</button>
                            </div>
                            </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function excluirPedido(pedidoID) {
            if (confirm("Tem certeza de que deseja excluir este pedido?")) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'del_pedido.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status == 200) {
                        location.reload();
                        alert('Pedido excluído com sucesso!');
                    } else {
                        alert('Ocorreu um erro ao excluir o pedido.');
                    }
                };
                xhr.send('pedidoID=' + pedidoID);
            }
        }
    </script>
</body>
</html>
