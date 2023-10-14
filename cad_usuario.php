<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuários e Clientes</title>
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
        input[type="email"],
        input[type="password"],
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

        .home-link {
            color: #08103B;
            text-decoration: none;
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .home-icon::after {
            content: "\00a0";
        }
    </style>
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
    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <div class="container">
        <h1>Cadastre-se</h1>
        <div id="message-dialog" title="Mensagem" style="display: none;">
            <p id="message-content"></p>
        </div>
        <form id="user-registration-form" action="inc_usuario.php" method="POST">
            <div class="fieldset">
                <span class="legend-text">Informações de Acesso</span>
                <p>
                <div class="row">
                    <div>
                        <label for="login">Nome de Usuário:</label>
                        <input type="text" id="login" name="login" required oninput="restrictMaxLength(this, 20)" placeholder="Escolha um nome de usuário">
                    </div>
                    <div style="position: relative;">
                        <label for="senha">Senha de Acesso:</label>
                        <div class="password-container">
                        <input type="password" id="senha" name="senha" required oninput="restrictMaxLength(this, 30)" placeholder="Escolha uma senha de acesso">
                        <i class="fas fa-eye-slash toggle-password-icon" id="toggle-password-icon" onclick="togglePasswordVisibility()"></i>
                    </div>
                </div>
            </div>
            <div class="btn-container">
                <button type="reset">Limpar</button>
                <button type="submit">Finalizar Cadastro</button>
            </div>
        </form>
    </div>
</body>
</html>