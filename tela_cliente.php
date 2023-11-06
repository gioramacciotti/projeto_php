<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

include('conexao.php');

// Recupere os clientes da tabela
$query = "SELECT * FROM clientes";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Painel de Clientes</title>
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
            Painel de Clientes
            <a href="cad_cliente.php" class="add-button">Incluir Cliente</a>
        </h1>
        <form method="post">
            <div class="filter-row">
            <div class="filter-field">
                <label for="filter-name">Filtrar por Nome:</label>
                <input type="text" name="filter-name" id="filter-name style="width: 10%">
            </div>
            <div class="filter-field">
                <label for="filter-city">Filtrar por Cidade:</label>
                <input type="text" name="filter-city" id="filter-city">
            </div>
            <div class="filter-button">
                <button type="submit" name="filter-submit" class="btn-filtro">Filtrar</button>
            </div>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Número</th>
                    <th>Bairro</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                    <th>E-mail</th>
                    <th>CPF/CNPJ</th>
                    <th>RG</th>
                    <th>Telefone</th>
                    <th>Celular</th>
                    <th>Data de Nascimento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['filter-submit'])) {
                    $filterName = $_POST['filter-name'];
                    $filterCity = $_POST['filter-city'];
                    $query = "SELECT * FROM clientes WHERE nome LIKE '%$filterName%' AND cidade LIKE '%$filterCity%'";
                } else {
                    $query = "SELECT * FROM clientes";
                }

                $result = mysqli_query($con, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['nome'] . "</td>";
                    echo "<td>" . $row['endereco'] . "</td>";
                    echo "<td>" . $row['numero'] . "</td>";
                    echo "<td>" . $row['bairro'] . "</td>";
                    echo "<td>" . $row['cidade'] . "</td>";
                    echo "<td>" . $row['estado'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['cpf_cnpj'] . "</td>";
                    echo "<td>" . $row['rg'] . "</td>";
                    echo "<td>" . $row['telefone'] . "</td>";
                    echo "<td>" . $row['celular'] . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['data_nasc'])) . "</td>";
                    echo "<td>
                            <div class='btn-container'>
                                <a href='cad_cliente.php?id=" . $row['id'] . "' class='btn-alterar'>Alterar</a>
                                <button class='btn-excluir' onclick='excluirCliente(" . $row['id'] . ")'>Excluir</button>
                            </div>
                            </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function excluirCliente(clienteID) {
            if (confirm("Tem certeza de que deseja excluir este cliente?")) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'del_cliente.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status == 200) {                    
                    location.reload();                    
                    alert('Cliente excluído com sucesso!');
                } else {
                    alert('Ocorreu um erro ao excluir o cliente.');
                }
            };
            xhr.send('clienteID=' + clienteID);
            }
        }
    </script>
</body>
</html>
