<?php
    @$msg_id = $_GET['msg_id'];
    @$nextPage = $_GET['page'];
    if($msg_id === null){
        echo "<script>window.location.href = 'index.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro Registrado</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon"></head>

<body>
    <?php require "help_files/menu.php" ?>
    <div class="container">
        <div class="main_form">
            <div class="responseMsg"></div>
        </div>
    </div>
    <script src="js/main.js"></script>
    <?php 
        switch ($msg_id) {
            case '1':
                echo "<script>finishAction('Você não tem possui permissão para acessar essa página!<br><br>Você será redirecionado para a página inicial em  ', 'index.php')</script>";
                break;
            case '2':
                echo "<script>finishAction('Você deve realizar uma busca primeiro!<br><br>Você será redirecionado para a página inicial em  ', 'index.php')</script>";
                break;
            case '3':
                echo "<script>finishAction('Houve Um Erro Desconhecido!<br><br>Você será redirecionado para a página inicial em  ', '$nextPage')</script>";
                break;
            default:
                break;
        }
    ?>
</body>
</html>