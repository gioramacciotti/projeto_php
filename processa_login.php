<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    require 'conexao.php'; // Include your database connection code

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $con = conectar(); // Call the conectar function to get the database connection

        // Query to retrieve user information
        $query = "SELECT id, senha FROM login_usuarios WHERE login = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            header("Location: inc_usuario.php");
            exit;
        } else {
            $_SESSION['msg'] = "<p style='color:red; text-align:center;'><b>Credenciais inválidas. Tente novamente.</b></p>";
            header("Location: login.php");
            exit;
        }
    } catch (mysqli_sql_exception $exception) {
        $_SESSION['msg'] = "<p style='color:red; text-align:center;'><b>Erro de banco de dados: " . $exception->getMessage() . "</b></p>";
        header("Location: login.php");
        throw $exception;
    } finally {
        mysqli_close($con);
    }
}
?>
