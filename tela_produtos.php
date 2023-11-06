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
            Painel de Produtos 
            <a href="cad_produto.php" class="add-button">Incluir Produto</a>
        </h1>
        <form method="post">
            <div class="filter-row">
            <div class="filter-field">
                <label for="filter-name">Filtrar por Nome:</label>
                <input type="text" name="filter-name" id="filter-name style="width: 10%">
            </div>
            <div class="filter-button">
                <button type="submit" name="filter-submit" class="btn-filtro">Filtrar</button>
            </div>
            </div>
        </form>

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
                if (isset($_POST['filter-submit'])) {
                    $filterName = $_POST['filter-name'];
                    $query = "SELECT * FROM produtos WHERE nome LIKE '%$filterName%'";
                } else {
                    $query = "SELECT * FROM produtos";
                }

                $result = mysqli_query($con, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['qtde_estoque'] . "</td>";
                    echo "<td>" . $row['valor_unitario'] . "</td>";
                    echo "<td>" . $row['unidade_medida'] . "</td>";
                    echo "<td>
                            <div class='btn-container'>
                                <a href='cad_produto.php?id=" . $row['id'] . "' class='btn-alterar'>Editar</a>
                                <button class='btn-excluir' onclick='excluirProduto(" . $row['id'] . ")'>Excluir</button>
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
                xhr.open('POST', 'del_produto.php', true);
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
