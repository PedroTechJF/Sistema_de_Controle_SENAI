<?php
    if (basename(__FILE__, '') === basename($_SERVER['SCRIPT_FILENAME'], '')) {
        echo "<script>window.location.href = '../erro.php?msg_id=1'</script>";
    }
    $server_addr = "localhost";
    $username = "root";
    $passwd = "";
    $db_name = "sis_control_senai";

    $mysql = mysqli_connect($server_addr, $username, $passwd, $db_name);

    if(mysqli_error($mysql)){
        echo "Houve um Erro de Conexão com o Banco de Dados. \n\n Mensagem de Erro: " . mysqli_error($mysql);
    }
?>