<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('conexao.php');

function generateSelectOptions($query, $con, $valueField, $textField, $valueField2 = null, $valueField3 = null, $selectedValue = null) {
    $result = mysqli_query($con, $query);
    $options = '<option value="" data-valor="" data-unidade=""></option>';

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $selected = ($selectedValue !== null && $row[$valueField] == $selectedValue) ? 'selected' : '';
            $options .= '<option value="' . $row[$valueField] . '" data-valor="' . $row[$valueField2] . '" data-unidade="' . $row[$valueField3] . '" ' . $selected . '>' . $row[$textField] . '</option>';
        }
    }

    return $options;
}

$alteracao = false;
$id_pedido = null;

if (isset($_GET['id'])) {
    $id_pedido = $_GET['id'];

    // Consulta na tabela 'pedidos' para obter outros dados do pedido
    $queryPedido = "SELECT id, data, id_cliente, observacao, cond_pagto, prazo_entrega FROM pedidos WHERE id = ?";
    $stmtPedido = mysqli_prepare($con, $queryPedido);
    mysqli_stmt_bind_param($stmtPedido, "d", $id_pedido);
    mysqli_stmt_execute($stmtPedido);
    $resultPedido = mysqli_stmt_get_result($stmtPedido);

    if ($resultPedido) {
        $rowPedido = mysqli_fetch_assoc($resultPedido);

        if ($rowPedido) {
            $pedido = $rowPedido;
            $alteracao = true;
        }
    }

    // Consulta na tabela 'itens_pedido' para obter itens do pedido
    $queryItensPedido = "SELECT id_produto, i.qtde, p.valor_unitario, p.unidade_medida 
                     FROM itens_pedido i
                     JOIN produtos p ON i.id_produto = p.id
                     WHERE i.id_pedido = ?";
    $stmtItensPedido = mysqli_prepare($con, $queryItensPedido);
    mysqli_stmt_bind_param($stmtItensPedido, "d", $id_pedido);
    mysqli_stmt_execute($stmtItensPedido);
    $resultItensPedido = mysqli_stmt_get_result($stmtItensPedido);

    $itensPedido = array();

    if ($resultItensPedido) {
        while ($rowItem = mysqli_fetch_assoc($resultItensPedido)) {
            $itensPedido[] = $rowItem;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Pedido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="style.css">
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
                <select name="produto_id[]" onchange="updateItemFields(this); updateTotalizadores();">
                <?php
                    $produtosQuery = "SELECT id, nome, valor_unitario, unidade_medida FROM produtos";
                    echo generateSelectOptions($produtosQuery, $con, 'id', 'nome', 'valor_unitario', 'unidade_medida', $itensPedido[0]['id_produto']);
                ?>
                </select>
                <label>Quantidade:</label>
                <input type="number" name="quantidade[]" min="1" value="0" onchange="updateTotalizadores();">
                <label>Valor Unitário:</label>
                <input type="number" step="0.01" name="valor_unitario[]" min="0" value="0" onchange="updateTotalizadores();">
                <label>Unidade de Medida:</label>
                <input type="text" name="unidade_medida[]">
                <span class="remove-item" onclick="removeItem(this); updateTotalizadores();">Remover</span>
            `;

            itemsContainer.appendChild(itemRow);
            
            // Atualiza os totalizadores
            updateTotalizadores();
        }

        function updateItemFields(selectElement) {
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var itemContainer = selectElement.parentElement;
            var valorInput = itemContainer.querySelector('input[name="valor_unitario[]"]');
            var unidadeInput = itemContainer.querySelector('input[name="unidade_medida[]"]');
            
            var valor = selectedOption.getAttribute('data-valor');
            var unidade = selectedOption.getAttribute('data-unidade');
            
            valorInput.value = valor;
            unidadeInput.value = unidade;
        }

        function removeItem(button) {
            var itemsContainer = document.getElementById("items-container");
            var itemRow = button.parentElement;
            itemsContainer.removeChild(itemRow);

            // Atualiza os totalizadores
            updateTotalizadores();
        }

        function updateTotalizadores() {
            var totalQuantidade = 0;
            var totalValor = 0;
            var totalItens = document.querySelectorAll('.items-row').length;

            document.querySelectorAll('.items-row').forEach(function (itemRow) {
                var quantidade = parseInt(itemRow.querySelector('input[name="quantidade[]"]').value);
                var valorUnitario = parseFloat(itemRow.querySelector('input[name="valor_unitario[]"]').value);

                totalQuantidade += quantidade;
                totalValor += quantidade * valorUnitario;
            });

            document.querySelector('input[name="total_quantidade"]').value = totalQuantidade;
            document.querySelector('input[name="total_valor"]').value = totalValor.toFixed(2);
            document.querySelector('input[name="total_itens"]').value = totalItens;
        }

        // Chamar a função quando os itens do pedido são carregados inicialmente
        document.addEventListener('DOMContentLoaded', function () {
            updateTotalizadores();
        });

    </script>
</head>
    <body>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
            <header>
                <div class="logo">
                    <img src="logo.png" alt="Verzi Websystem">
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
                    <img src="logo.png" alt="Verzi Websystem">
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
                <?php echo $alteracao ? 'Alteração' : 'Inclusão'; ?> de Pedido
            </h1>
            <form id="user-registration-form" action="<?php echo $alteracao ? 'alt_pedido.php' : 'inc_pedido.php' ?>" method="POST">
                <input type="hidden" name="id_pedido" value="<?php echo $id_pedido; ?>">
                <div class="fieldset">
                    <span class="legend-text">Informações do Pedido</span>
                    <p>
                    <div>
                        <label>Cliente:</label>
                        <select name="cliente_id" required>
                            <?php echo generateSelectOptions("SELECT id, nome FROM clientes", $con, 'id', 'nome', null, null, $alteracao ? $pedido['id_cliente'] : null); ?>
                        </select>
                        <label>Data:</label>
                        <input type="date" name="data" required value="<?php echo $alteracao ? date('Y-m-d', strtotime($pedido['data'])) : ''; ?>">
                        <label>Observação:</label>
                        <input type="text" name="observacao" placeholder="Informe uma observação (opcional)" value="<?php echo $alteracao ? $pedido['observacao'] : ''; ?>">
                        <label>Condição de Pagamento:</label>
                        <input type="text" name="cond_pagto" required value="<?php echo $alteracao ? $pedido['cond_pagto'] : ''; ?>">
                        <label>Prazo de Entrega:</label>
                        <input type="text" name="prazo_entrega" required value="<?php echo $alteracao ? $pedido['prazo_entrega'] : ''; ?>">
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
                            <?php
                            $index = 1;
                            foreach ($itensPedido as $item) {
                                echo '<div class="items-row" id="item-' . $index . '">';
                                echo '<label>Produto:</label>';
                                echo '<select name="produto_id[]" onchange="updateItemFields(this); updateTotalizadores();">';
                                $produtosQuery = "SELECT id, nome, valor_unitario, unidade_medida FROM produtos";
                                echo generateSelectOptions($produtosQuery, $con, 'id', 'nome', 'valor_unitario', 'unidade_medida', $item['id_produto']);
                                echo '</select>';
                                echo '<label>Quantidade:</label>';
                                echo '<input type="number" name="quantidade[]" min="1" value="' . $item['qtde'] . '" onchange="updateTotalizadores();">';
                                echo '<label>Valor Unitário:</label>';
                                echo '<input type="number" step="0.01" name="valor_unitario[]" min="0" value="' . $item['valor_unitario'] . '" onchange="updateTotalizadores();">';
                                echo '<label>Unidade de Medida:</label>';
                                echo '<input type="text" name="unidade_medida[]" value="' . $item['unidade_medida'] . '">';
                                echo '<span class="remove-item" onclick="removeItem(this); updateTotalizadores();">Remover</span>';
                                echo '</div>';
                                $index++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="fieldset" id="totalizadores">
                    <span class="legend-text">Totalizadores do Pedido</span>
                    <p>
                        <div class="fields-row" id="fields-1">
                            <label>Quantidade total: </label>
                            <input type="text" name="total_quantidade" style="width: 90px" readonly>

                            <label>Valor total: </label>
                            <input type="text" name="total_valor" style="width: 90px" readonly>

                            <label>Total de itens: </label>
                            <input type="text" name="total_itens" style="width: 90px" readonly>
                        </div>
                    </p>
                </div>
                <div class="btn-container">
                    <button type="reset" style="width:48%;">Limpar</button>
                    <button type="submit" style="width:48%;">Incluir</button>
                </div>
            </form>
        </div>
    </body>
</html>