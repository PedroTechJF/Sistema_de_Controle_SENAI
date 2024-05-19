<?php
    @$usuario = $_GET['user'];
    @$senha = $_GET['pass'];
    @$perfil = $_GET['perfil'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização de Senha</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon"></head>
<body>
    <?php
        @$login = $_COOKIE['login'];
        require "help_files/menu.php" 
    ?>
    <div class="container">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
        ?>
                <div class="main_form">
                    <h1>Atualização de Senha</h1>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form">
                        <label for="usuario">Usuário</label>
                        <input type="text" name="usuario" id="usuario" value="<?php echo $usuario; ?>">
                        <label for="old_senha">Senha</label>
                        <input type="password" name="old_senha" id="old_senha" <?php echo isset($senha) ? "value=$senha readonly" : "value=''" ?>>
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" name="nova_senha" id="nova_senha">
                        <input type="text" name="perfil" value="<?php echo $perfil ?>" hidden>
                        <input type="button" value="Atualizar" class="submitBtn">
                    </form>
        <?php
            }
        ?>
            <div class="responseMsg"></div>
        </div>
    </div>
    <script src="js/main.js"></script>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require 'help_files/db.php';
            date_default_timezone_set('America/Sao_Paulo');
            @$httpFile = basename($_SERVER['SCRIPT_FILENAME'], '');
            @$httpSearch = explode('?', $_SERVER['HTTP_REFERER'])[1];
            @$httpRequest = isset($httpSearch) === true ? "$httpFile?$httpSearch" : "$httpFile";

            $usuario = $_POST['usuario'];
            $senha = $_POST['old_senha'];
            $nova_senha = $_POST['nova_senha'];
            $senha_md5 = strlen($senha) <= 30 ? md5($senha) : $senha;
            $nova_senha_md5 = md5($nova_senha);
            
            $perfil_post = $_POST['perfil'];
            $perfil_min = $perfil_post !== "alunos" ? "_".substr($perfil_post, 0, 3) : "";

            if(isset($perfil_post)){
                @$usuario = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM usuarios_$perfil_post WHERE login = '$usuario' AND senha = '$senha_md5'"));
                @$id = $usuario[substr($perfil_post, 0, -1) . "_id"];
                @$dados = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM $perfil_post WHERE id = '$id'"));
                @$senha_o = explode('.', explode('@', $dados['email'])[1])[0] . "_" . substr($dados['cnpj'], 10, 14);
                @$senha_o_md5 = md5($senha_o);
    
                if(!isset($usuario)){
                    echo "<script>finishAction('Usuário ou Senha Incorretos!<br><br>A página irá recarregar em ', '$httpRequest')</script>";
                    die();
                } else if($senha_md5 === $nova_senha_md5){
                    echo "<script>finishAction('A nova senha deve ser diferente da atual!<br><br>A página irá recarregar em ', '$httpRequest')</script>";
                    die();
                } else if($senha_o_md5 === $nova_senha_md5){
                    echo "<script>finishAction('A nova senha não pode ser igual a senha original!<br><br>A página irá recarregar em ', '$httpRequest')</script>";
                    die();
                }
    
                $id_usuario = $usuario['id'];
                $query_editarUsuario = "UPDATE usuarios_$perfil_post SET senha = '$nova_senha_md5' WHERE id = '$id_usuario'";
                $update_editarUsuario = mysqli_query($mysql, $query_editarUsuario);
    
                if ($update_editarUsuario){
                    unset($_COOKIE['login']);
                    setcookie('login', '', -1);
                    echo "<script>finishAction('Senha atualizada com Sucesso!<br><br>Você será redirecionado para a tela de login em ', 'login$perfil_min.php')</script>";
                } else {
                    echo "<script>finishAction('Não foi possível realizar a alteração de senha!<br><br>A página irá recarregar em ', '$httpRequest')</script>";
                }
            } else {
                echo "<script>window.location.href = 'erro.php?msg_id=3&page=atualizar_senha.php'</script>";
            }
        }
    ?>
</body>
</html>