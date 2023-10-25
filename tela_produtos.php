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

// Recupere os produtos da tabela
$query = "SELECT id, nome, qtde_estoque, valor_unitario, unidade_medida FROM produtos";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Painel de Produtos</title>
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
            width: 100%;
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
            justify-content: space-around;
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

        .home-link,
        .sign-out-link {
            color: #08103B;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .alterar-button,
        .excluir-button {
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

        .alterar-button {
            background-color: #136DAF;
            width: 50px;
        }

        .excluir-button:hover {
            background-color: #D32F2F;
        }

        .alterar-button:hover {
            background-color: #033255;
        }        

        .add-produto-button {
            background-color: #136DAF;
            color: #fff;
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 20px;
            float: right; /* Move o botão para o canto superior direito */
        }

        .add-produto-button:hover {
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
            Painel de Produtos 
            <a href="cad_produto.php" class="add-produto-button">Incluir Produto</a></h1>
        <table>
            <thead>
                <tr>
                    <th>ID do Produto</th>
                    <th>Nome</th>
                    <th>Quantidade em Estoque</th>
                    <th>Valor Unitário</th>
                    <th>Unidade de Medida</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['qtde_estoque'] . "</td>";
                    echo "<td>" . $row['valor_unitario'] . "</td>";
                    echo "<td>" . $row['unidade_medida'] . "</td>";
                    echo "<td>
                            <div class='btn-container'>
                                <a href='cad_produto.php?id=" . $row['id'] . "' class='alterar-button'>Editar</a>
                                <button class='excluir-button' onclick='excluirProduto(" . $row['id'] . ")'>Excluir</button>
                            </div>
                            </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function excluirProduto(produtoID) {
            if (confirm("Tem certeza de que deseja excluir este produto?")) {
                // Enviar uma solicitação AJAX para o arquivo PHP de exclusão
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'excluir_produto.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status == 200) {
                        location.reload();
                        alert('Produto excluído com sucesso!');
                    } else {
                        alert('Ocorreu um erro ao excluir o produto.');
                    }
                };
                xhr.send('produtoID=' + produtoID);
            }
        }
    </script>
</body>
</html>
