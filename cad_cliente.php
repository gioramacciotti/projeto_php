<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('conexao.php');

$cliente = array(
    'nome' => '',
    'data_nasc' => '',
    'endereco' => '',
    'numero' => '',
    'bairro' => '',
    'cidade' => '',
    'estado' => '',
    'email' => '',
    'cpf_cnpj' => '',
    'rg' => '',
    'telefone' => '',
    'celular' => '',
    'login' => '',
);

$alteracao = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Consulta na tabela 'clientes' para obter outros dados do cliente
    $queryCliente = "SELECT * FROM clientes WHERE id = ?";
    $stmtCliente = mysqli_prepare($con, $queryCliente);
    mysqli_stmt_bind_param($stmtCliente, "d", $id);
    mysqli_stmt_execute($stmtCliente);
    $resultCliente = mysqli_stmt_get_result($stmtCliente);

    if ($resultCliente) {
        $rowCliente = mysqli_fetch_assoc($resultCliente);
        
        if ($rowCliente) {
            $cliente = $rowCliente;
            $alteracao = true;
        }
    }
    
    // Consulta na tabela 'login_usuarios' para obter login
    $queryLogin = "SELECT login FROM login_usuarios WHERE id_cliente = ?";
    $stmtLogin = mysqli_prepare($con, $queryLogin);
    mysqli_stmt_bind_param($stmtLogin, "d", $id);
    mysqli_stmt_execute($stmtLogin);
    $resultLogin = mysqli_stmt_get_result($stmtLogin);

    if ($resultLogin) {
        $rowLogin = mysqli_fetch_assoc($resultLogin);
        
        if ($rowLogin) {
            $cliente['login'] = $rowLogin['login'];
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuários e Clientes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("senha");
            const toggleIcon = document.getElementById("toggle-password-icon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            }
        }

        function restrictMaxLength(input, maxLength) {
            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength);
            }
        }

        function clearForm() {
            document.getElementById("user-registration-form").reset();
        }
    </script>
</head>

<body>
    <?php
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    ?>
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

    <?php
    } else {
    ?>    
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

    <?php    
    }
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <div class="container">
        <h1>
            <a href="tela_cliente.php" class="back-link">
            <i class="fas fa-arrow-left back-icon" onclick="window.location='tela_clientes.php';"></i>
            </a>
            <?php echo $alteracao ? 'Atualizar Cadastro' : 'Cadastrar' ?></h1>
        <div id="message-dialog" title="Mensagem" style="display: none;">
            <p id="message-content"></p>
        </div>
        <form id="user-registration-form" action="<?php echo $alteracao ? 'alt_cliente.php' : 'inc_cliente.php' ?>" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>"> <!-- Adição do campo ID oculto -->
            <div class="fieldset">
                <span class="legend-text">Informações Pessoais</span>
                <p>
                <div>
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required oninput="restrictMaxLength(this, 100)" placeholder="Informe o nome completo" value="<?php echo $cliente['nome']; ?>">
                    <label for="data_nasc">Data de Nascimento:</label>
                    <input type="date" name="data_nasc" required placeholder="dd/mm/yyyy" value="<?php echo $cliente['data_nasc']; ?>">       
                    <label for="cpf_cnpj">CPF ou CNPJ:</label>
                    <input type="text" id="cpf_cnpj" name="cpf_cnpj" required oninput="restrictMaxLength(this, 14)" placeholder="Informe o CPF ou CNPJ" value="<?php echo $cliente['cpf_cnpj']; ?>">
                    <label for="rg">Número de RG:</label>
                    <input type="text" id="rg" name="rg" required oninput="restrictMaxLength(this, 10)" placeholder="Informe o número de RG" value="<?php echo $cliente['rg']; ?>">
                </div>
            </div>
            <div class="fieldset">
                <span class="legend-text">Endereço</span>
                <p>
                <div class="row">
                    <div>
                        <label for="endereco">Endereço:</label>
                        <input type="text" id="endereco" name="endereco" required oninput="restrictMaxLength(this, 100)" placeholder="Informe o endereço" value="<?php echo $cliente['endereco']; ?>">
                        <label for="numero">Número:</label>
                        <input type="text" id="numero" name="numero" required oninput="restrictMaxLength(this, 10)" placeholder="Informe o número" value="<?php echo $cliente['numero']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="bairro">Bairro:</label>
                        <input type="text" id="bairro" name="bairro" required oninput="restrictMaxLength(this, 50)" placeholder="Informe o bairro" value="<?php echo $cliente['bairro']; ?>">
                    </div>
                    <div>
                        <label for="cidade">Cidade:</label>
                        <input type="text" id="cidade" name="cidade" required oninput="restrictMaxLength(this, 60)" placeholder="Informe a cidade" value="<?php echo $cliente['cidade']; ?>">
                    </div>
                    <div>
                        <label for="estado">Estado:</label>
                        <select id="estado" name="estado" required>
                            <option value="SP" <?php if ($cliente['estado'] === 'SP') echo 'selected'; ?>>SP</option>
                            <option value="RJ" <?php if ($cliente['estado'] === 'RJ') echo 'selected'; ?>>RJ</option>
                            <option value="MG" <?php if ($cliente['estado'] === 'MG') echo 'selected'; ?>>MG</option>
                            <option value="RS" <?php if ($cliente['estado'] === 'RS') echo 'selected'; ?>>RS</option>
                            <option value="PR" <?php if ($cliente['estado'] === 'PR') echo 'selected'; ?>>PR</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="fieldset">
                <span class="legend-text">Informações de Contato</span>
                <p>
                <div class="row">
                    <div>
                        <label for="email">E-mail de Contato:</label>
                        <input type="email" id="email" name="email" required oninput="restrictMaxLength(this, 100)" placeholder="Informe o e-mail de contato" value="<?php echo $cliente['email']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="telefone">Telefone de Contato:</label>
                        <input type="text" id="telefone" name="telefone" oninput="restrictMaxLength(this, 10)" placeholder="Informe o telefone de contato" value="<?php echo $cliente['telefone']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="celular">Celular:</label>
                        <input type="text" id="celular" name="celular" oninput="restrictMaxLength(this, 11)" placeholder="Informe o número de celular" value="<?php echo $cliente['celular']; ?>">
                    </div>
                </div>
            </div>
            <div class="fieldset">
                <span class="legend-text">Informações de Acesso</span>
                <p>
                <div class="row">
                    <div>
                        <label for="login">Nome de Usuário:</label>
                        <input type="text" id="login" name="login" required oninput="restrictMaxLength(this, 20)" placeholder="Escolha um nome de usuário" value="<?php echo $cliente['login']; ?>">
                    </div>
                    <div style="position: relative;">
                        <label for="senha">Senha de Acesso:</label>
                        <div class="password-container">
                        <input type="password" id="senha" name="senha" required oninput="restrictMaxLength(this, 30)" placeholder="Informe uma senha de acesso">
                        <i class="fas fa-eye-slash toggle-password-icon" id="toggle-password-icon" onclick="togglePasswordVisibility()"></i>
                    </div>
                </div>
            </div>
            <div class="btn-container">
                <button type="reset" style="width:48%;">Limpar</button>
                <button type="submit" style="width:48%;"><?php echo $alteracao ? 'Atualizar Cadastro' : 'Finalizar Cadastro' ?></button>
            </div>
        </form>
    </div>
</body>
</html>
