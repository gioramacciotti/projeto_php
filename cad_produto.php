<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('conexao.php');

$produto = array(
    'nome' => '',
    'qtde_estoque' => '',
    'valor_unitario' => '',
    'unidade_medida' => ''
);

$alteracao = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Consulta na tabela 'produtos' para obter outros dados do produto
    $queryProduto = "SELECT * FROM produtos WHERE id = ?";
    $stmtProduto = mysqli_prepare($con, $queryProduto);
    mysqli_stmt_bind_param($stmtProduto, "d", $id);
    mysqli_stmt_execute($stmtProduto);
    $resultProduto = mysqli_stmt_get_result($stmtProduto);

    if ($resultProduto) {
        $rowProduto = mysqli_fetch_assoc($resultProduto);
        
        if ($rowProduto) {
            $produto = $rowProduto;
            $alteracao = true;
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
            width: 70%;
            margin: 20px auto;
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
        input[type="number"],
        input[type="email"],
        select {
            width: 98%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .fieldset {
            border: 2px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .legend-text {
            font-weight: bold;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
        }

        button {
            background-color: #136DAF;
            color: #fff;
            padding: 15px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 48%;
        }

        button:hover {
            background-color: #033255;
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

        .home-link,
        .sign-out-link {
            color: #08103B;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .home-icon::after,
        .sign-out-icon::after {
            content: "\00a0";
        }      

        .back-icon {
            color: #136DAF; 
            cursor: pointer;
            float: left;
        }
    </style>
    <script>        
        function clearForm() {
            document.getElementById("user-registration-form").reset();
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
    <?php
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    ?>
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

    <?php
    } else {
    ?>    
    <header>
        <div class "logo">
            <img src="logo.png" alt="Verzi Websystem" style="height: 40px;">
        </div>        
        <div style="display: flex; align-items: center;">
            <a href="login.php" class="home-link">
                <i class="fas fa-home"></i>&nbsp;Início
            </a>
        </div>
    </header>

    <?php    
    }
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <div class="container">
        <h1>
            <a href="tela_produtos.php" class="back-link">
            <i class="fas fa-arrow-left back-icon" onclick="window.location='tela_produtos.php';"></i>
            </a>
            <?php echo $alteracao ? 'Atualizar Produto' : 'Cadastrar Produto' ?></h1>
        <div id="message-dialog" title="Mensagem" style="display: none;">
            <p id="message-content"></p>
        </div>
        <form id="user-registration-form" action="<?php echo $alteracao ? 'alt_produto.php' : 'inc_produto.php' ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="fieldset">
                <span class="legend-text">Informações do Produto</span>
                <p>
                <div>
                    <label for="nome">Nome do Produto:</label>
                    <input type="text" id="nome" name="nome" required oninput="restrictMaxLength(this, 255)" placeholder="Informe o nome do produto" value="<?php echo $produto['nome']; ?>">
                    <label for="qtde_estoque">Quantidade em Estoque:</label>
                    <input type="number" name="qtde_estoque" required placeholder="Informe a quantidade em estoque" value="<?php echo $produto['qtde_estoque']; ?>">
                    <label for="valor_unitario">Valor Unitário:</label>
                    <input type="text" name="valor_unitario" required placeholder="Informe o valor unitário" value="<?php echo $produto['valor_unitario']; ?>">
                    <label for="unidade_medida">Unidade de Medida:</label>
                    <input type="text" name="unidade_medida" required oninput="restrictMaxLength(this, 20)" placeholder="Informe a unidade de medida" value="<?php echo $produto['unidade_medida']; ?>">
                </div>
            </div>
            <div class="btn-container">
                <button type="reset">Limpar</button>
                <button type="submit"><?php echo $alteracao ? 'Atualizar Produto' : 'Finalizar Cadastro' ?></button>
            </div>
        </form>
    </div>
</body>
</html>
