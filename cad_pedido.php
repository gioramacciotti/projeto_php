<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('conexao.php');

function generateSelectOptions($query, $con, $valueField, $textField) {
    $result = mysqli_query($con, $query);
    $options = '';

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= '<option value="' . $row[$valueField] . '">' . $row[$textField] . '</option>';
        }
    }

    return $options;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cadastro de Pedido</title>
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
            input[type="date"],
            input[type="number"],
            select {
                width: -webkit-fill-available;
                padding: 12px;
                border: 1px solid #ccc;
                border-radius: 6px;
                margin-bottom: 10px;
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

            .items-row {
                display: flex;
                align-items: center;
                margin-bottom: 10px;            
                justify-content: space-between;
            }
            
            .items-row select {
                width: -webkit-fill-available;
                margin-right: 10px;            
                margin-bottom: 0px;
            }

            .items-row label{
                width: auto;
                margin-right: 10px;
                margin-bottom: 0px;          
            }

            .items-row input{
                width: 65px;
                margin-right: 10px;
                margin-bottom: 0px;          
            }

            .remove-item {
                color: red;
                cursor: pointer;
            }

            .add-item-button {
                float: right;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background-color: #136DAF;
                color: #fff;
                font-size: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
            }

            .add-item-button:hover {
                background-color: #033255;
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

            function addEmptyItem() {
                var itemsContainer = document.getElementById("items-container");
                var itemRow = document.createElement("div");
                itemRow.className = "items-row";
                itemRow.innerHTML = `
                    <label>Produto:</label>
                    <select name="produto_id[]" onchange="updateValor(this)">
                        <?php echo generateSelectOptions("SELECT id, nome, valor_unitario FROM produtos", $con, 'id', 'nome'); ?>
                    </select>
                    <label>Quantidade:</label>
                    <input type="number" name="quantidade[]" min="1" value="1">
                    <label>Valor Unitário:</label>
                    <input type="number" step="0.01" name="valor_unitario[]" min="0" value="0" readonly>                
                    <label>Unidade de Medida:</label>
                    <input type="text" name="unidade_medida[]" readonly>
                    <span class="remove-item" onclick="removeItem(this)">Remover</span>
                `;

                itemsContainer.appendChild(itemRow);
            }

            function removeItem(button) {
                var itemsContainer = document.getElementById("items-container");
                var itemRow = button.parentElement;
                itemsContainer.removeChild(itemRow);
            }

            function updateValor(selectElement) {
                var selectedOption = selectElement.options[selectElement.selectedIndex];
                var valorInput = selectElement.parentElement.querySelector('input[name="valor_unitario[]"]');
                var valor = selectedOption.getAttribute('data-valor');
                valorInput.value = valor;
            }

            function updateProductDetails(itemId, selectElement) {
                var selectedProductId = selectElement.value;

                // Crie uma solicitação AJAX para buscar informações do produto no banco de dados
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'verifica_prod.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status == 200) {
                        // A resposta da solicitação AJAX deve conter os detalhes do produto
                        var response = JSON.parse(xhr.responseText);
                        var itemContainer = document.querySelector("#item-" + itemId);
                        var valorInput = itemContainer.querySelector('input[name="valor_unitario[]"]');
                        var unidadeInput = itemContainer.querySelector('input[name="unidade_medida[]"]');
                        valorInput.value = response.valor_unitario;
                        unidadeInput.value = response.unidade_medida;
                    } else {
                        alert('Ocorreu um erro ao buscar os detalhes do produto.');
                    }
                };
                xhr.send('produtoID=' + selectedProductId);
            }

        </script>
    </head>

    <body>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
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
        <?php } else { ?>
            <header>
                <div class="logo">
                    <img src="logo.png" alt="Verzi Websystem" style="height: 40px;">
                </div>
                <div style="display: flex; align-items: center;">
                    <a href="login.php" class="home-link">
                        <i class="fas fa-home"></i>&nbsp;Início
                    </a>
                </div>
            </header>
        <?php } ?>

        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>

        <div class="container">
            <h1>
                <a href="tela_pedidos.php" class="back-link">
                    <i class="fas fa-arrow-left back-icon" onclick="window.location='tela_pedidos.php';"></i>
                </a>
                Inclusão de Pedido
            </h1>
            <form id="user-registration-form" action="inc_pedido.php" method="POST">
                <div class="fieldset">
                    <span class="legend-text">Informações do Pedido</span>
                    <p>
                    <div>
                        <label>Cliente:</label>
                        <select name="cliente_id" required>
                            <?php echo generateSelectOptions("SELECT id, nome FROM clientes", $con, 'id', 'nome'); ?>
                        </select>
                        <label>Data:</label>
                        <input type="date" name="data" required>
                        <label>Observação:</label>
                        <input type="text" name="observacao" placeholder="Informe uma observação (opcional)">
                        <label>Condição de Pagamento:</label>
                        <input type="text" name="cond_pagto" required>
                        <label>Prazo de Entrega:</label>
                        <input type="text" name="prazo_entrega" required>
                    </div>
                </div>
                <div class="fieldset">
                    <span class="legend-text">Itens do Pedido</span>                
                    <button type="button" class="add-item-button" onclick="addEmptyItem()">
                        <i class="fas fa-plus"></i>
                    </button>
                    <p>
                    <div>
                        <div id="items-container" style="margin-top: 35px; width=auto;">
                            <div class="items-row" id="item-1">
                                <label>Produto:</label>
                                <select name="produto_id[]" onchange="updateProductDetails(1)">
                                    <?php echo generateSelectOptions("SELECT id, nome FROM produtos", $con, 'id', 'nome'); ?>
                                </select>
                                <label>Quantidade:</label>
                                <input type="number" name="quantidade[]" min="1" value="1">
                                <label>Valor Unitário:</label>
                                <input type="number" step="0.01" name="valor_unitario[]" min="0" value="0" readonly>
                                <label>Unidade de Medida:</label>
                                <input type="text" name="unidade_medida[]" readonly>
                                <span class="remove-item" onclick="removeItem(1)">Remover</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-container">
                    <button type="reset">Limpar</button>
                    <button type="submit">Incluir</button>
                </div>
            </form>
        </div>
    </body>
</html>