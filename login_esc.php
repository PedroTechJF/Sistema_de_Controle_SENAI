<?php
    @$login = $_COOKIE['login'];
    @$login_data = explode(",", $login);
    if (isset($login)) {
        echo "<script>window.location.href = 'index.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon"></head>
<body>
    <?php require "help_files/menu.php" ?>
    <div class="container">
        <div class="main_form">
            <h1>Login</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="form">
                <label for="login">Usuário</label> 
                <input type="text" name="login" id="login">
                <label for="senha">Senha</label> 
                <div class="pass">
                        <input type="password" name="senha" id="senha">
                        <i class="controlPass fa-solid fa-eye" id="showPass"></i>
                        <i class="controlPass fa-solid fa-eye-slash" id="hidePass" style="display: none;"></i>
                    </div>
                <input type="text" name="perfil" id="perfilFinal" value="escolas" hidden>
                <input type="button" class="submitBtn" value="Entrar">
            </form>
            <div class="extraBtns">
                <a href="atualizar_senha.php?perfil=escolas" class="a_login">Esqueceu sua Senha?</a>        
            </div>
            <div class="responseMsg"></div>
        </div>
        <div class="extraBtns bottomBtns">
            <a href="login.php" class="a_login">Sou Aluno</a>
            <p style="margin: 0">/</p>
            <a href="login_emp.php" class="a_login">Sou Empresa</a>
        </div>
    </div>
    <script src="js/main.js"></script>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require "help_files/login_geral.php";
    }
?>