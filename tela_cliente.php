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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin: 20px auto;
            max-width: 90%; /* Define a largura máxima do container */
            overflow-x: auto;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        label {
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="password"],
        select {
            width: 98%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
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
            justify-content: space-between;
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

        .home-link,
        .sign-out-link {
            color: #08103B;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-right: 15px;
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
        <h1>Painel de Clientes</h1>
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
                    echo "<td>" . $row['data_nasc'] . "</td>";
                    echo "<td>
                            <div class='btn-container'>
                                <a href='cad_cliente.php?id=" . $row['id'] . "' class='btn-alterar'>Alterar</a>
                                <button style='background-color: #FF0000; color: #fff;' class='btn-excluir' onclick='excluirCliente(" . $row['id'] . ")'>Excluir</button>
                            </div>
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
