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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        body {
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
            width: 27%;
            margin: auto;
            border: 4px solid #033255;
        }

        h1 {
            color: #333;
            text-align: center;
        }
        
        h2 {
            text-align: center;
            font-weight: normal;
            font-size: 14px;
        }

        label {
            color: #333;
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;            
            box-sizing: border-box;
        }

        .fieldset {
            border-radius: 8px;
            padding: 20px;
            padding-bottom: 0px;
        }

        .legend-text {
            font-weight: bold;
        }

        .btn-container {
            display: flex;
            justify-content: center;
        }

        button {
            background-color: #136DAF;
            color: #fff;
            padding: 15px 20px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            width: 40%;
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
            justify-content: center; 
            align-items: center;
            margin-bottom: 20px; 
        }

        .logo img {
            height: 60% !important;
            width: 60%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
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
                <button type="submit">Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>
