<?php
    @$login = $_COOKIE['login'];
    @$login_data = explode(",", $login);
    if (!isset($login)) {
        echo "<script>window.location.href = 'login.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Solicitações</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
<body>
    <?php require "help_files/menu.php" ?>
    <div class="container">
        <?php 
            require "help_files/db.php";
            $id_inst = $login_data[2];
            $solicitacao_type = "geral";
            $perfil = substr($login_data[3], 0, -1);
            @$get_solicitacao_id = $_GET['sol_id'];
            @$get_solicitacao_type = $_GET['sol_type'];
            $query_select = "SELECT * FROM solicitacoes";

            if($login_data[3] !== 'escolas'){
                $query_select = "SELECT * FROM solicitacoes WHERE " . $perfil . "_id = '$id_inst'";
                if($login_data[3] === 'empresas'){
                    $query_select = "SELECT * FROM solicitacoes WHERE " . $perfil . "_id = '$id_inst' AND tipo_solicitacao = 'Justificativa de Falta' AND db_child = 'justificativas_faltas'";
                }
            }

            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                $query_select = "SELECT * FROM solicitacoes WHERE " . $perfil . "_id = '$id'";
            }

            if(isset($_GET['alu_id'])) {
                $id = $_GET['alu_id'];
                $query_select = "SELECT * FROM solicitacoes WHERE aluno_id = '$id'";
            }

            if(isset($get_solicitacao_id) && isset($get_solicitacao_type)) {
                $query_select = "SELECT * FROM $get_solicitacao_type WHERE solicitacao_id = '$get_solicitacao_id'";
                $solicitacao_type = $get_solicitacao_type;
            }

            $select_result = mysqli_query($mysql, $query_select);
            $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);

            if(count($array_select) > 0){
                require "help_files/solicitacoes__$solicitacao_type.php";
            } else {
                $msg = $login_data[3] === 'empresas' ? "Não Há Nenhum Atestado Enviado!" : "Não Há Nenhuma Solicitação Enviada!";
                echo "<p>$msg</p>";
            }
            mysqli_close($mysql);
            ?>
    </div>
    <script src="js/main.js"></script>
</body>
</html>