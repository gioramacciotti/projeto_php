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
    <link rel="stylesheet" type="text/css" href="style.css">
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
                <button type="submit"><?php echo $alteracao ? 'Atualizar Produto' : 'Incluir Produto' ?></button>
            </div>
        </form>
    </div>
</body>
</html>
