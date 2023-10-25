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

// Recupere os pedidos da tabela
$query = "SELECT p.id, p.data, c.nome AS nome_cliente, p.observacao, p.cond_pagto, p.prazo_entrega FROM pedidos p
          JOIN clientes c ON p.id_cliente = c.id";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Painel de Pedidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #ffffff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 50px;
        }

        .container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin: 20px auto;
            max-width: 90%;
            overflow-x: auto;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        table {
            width: -webkit-fill-available;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 7px;
            text-align: left;
        }

        th {
            background-color: #033255;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .btn-container {
            display: flex;
            justify-content: flex-start;
        }

        button {
            background-color: #136DAF;
            color: #fff;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 48%;
        }

        button:hover {
            background-color: #033255;
        }

        .toggle-password-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
        }

        .home-link,
        .sign-out-link {
            color: #08103B;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .btn-alterar,
        .btn-excluir {
            background-color: #FF0000;
            color: #fff;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 95px;
            transition: background-color 0.3s;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        .btn-alterar {
            background-color: #136DAF;
            width: 50px;
        }

        .btn-excluir:hover {
            background-color: #D32F2F;
        }

        .btn-alterar:hover {
            background-color: #033255;
        }

        .btn-excluir {
            margin-left: 2%;
        }

        .filter-row {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .filter-field {
            width: 20%;
            margin-right: 50px;
        }

        .filter-button {
            flex: 0;
            padding-top: 28px;
        }

        .btn-filtro {
            width: 150px;
            height: 42px;
            padding: 12px;
            font-weight: bold;
            font-size: 16px;
        }

        .add-pedido-button {
            background-color: #136DAF;
            color: #fff;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 20px;
            float: right; 
        }

        .add-pedido-button:hover {
            background-color: #033255;
        }        

        .back-icon {
            color: #136DAF; 
            cursor: pointer;
            float: left;
        }
    </style>
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
            <a href="cad_pedido.php" class="add-pedido-button">Incluir Pedido</a></h1>
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
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['data'] . "</td>";
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
                // Enviar uma solicitação AJAX para o arquivo PHP de exclusão
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
