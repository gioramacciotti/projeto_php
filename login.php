<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Cadastro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 30%;
            margin: auto;
            border: 4px solid #033255;
        }      
        .login-logo {
            display: flex;
            justify-content: center; 
            align-items: center;
            margin-bottom: 20px; 
        }

        .login-logo img {
            height: 50% !important;
            width: 50%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-logo">
            <img src="logo.png" alt="Verzi Websystem" style="height: 40px;">
        </div>
        <h1>Bem-vindo</h1>
        <h2>Efetue o login para acessar o sistema</h2><p></p>

        <form action="processa_login.php" method="POST">
            <div class="fieldset">
                <div class="row">
                    <div>
                        <input type="text" id="login" name="username" required placeholder="Seu nome de usuário">
                    </div>
                </div>
                <div class="row">
                    <div>
                        <input type="password" id="senha" name="password" placeholder="Sua senha de acesso">
                    </div>
                </div>
            </div>            
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?><br>
            <div class="btn-container">
                <button type="submit">ENTRAR</button>
            </div><br>
            <p style="text-align: center; margin-top: 10px;">
                Não tem uma conta? <a href="cad_cliente.php">Registre-se</a>
            </p>
        </form>
    </div>
</body>
</html>
