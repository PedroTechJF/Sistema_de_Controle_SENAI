<?php
    if (basename(__FILE__, '') === basename($_SERVER['SCRIPT_FILENAME'], '')) {
        echo "<script>window.location.href = 'erro.php?msg_id=1'</script>";
    }
    require "db.php";

    $login = $_POST['login'];
    $senha = md5($_POST['senha']);
    $perfil = $_POST['perfil'];
    $tabela = 'usuarios_' . $perfil;
    $id_name = substr($perfil, 0, -1) . '_id';
    $query_select = "SELECT * FROM " . $tabela . " WHERE login = '$login' AND senha = '$senha'";
    // echo $query_select;

    $select_result = mysqli_query($mysql, $query_select);
    $array_select = mysqli_fetch_array($select_result);

    if ($array_select === false || $array_select === null) {
        echo "<script>responseMsgField.innerHTML = 'Usuário ou Senha Incorretos!'</script>";
        die();   
    } else {
        $id_user = $array_select[$id_name];
        $query_userData = "SELECT * FROM $perfil WHERE id = '$id_user'";
        $userData_result = mysqli_query($mysql, $query_userData);
        $array_userData = mysqli_fetch_array($userData_result);
        
        @$email = $array_userData["email"];
        if($perfil === 'empresas'){
            @$cnpj = $array_userData["cnpj"];
            @$senha_o = explode('.', explode('@', $email)[1])[0] . "_" . substr($cnpj, 10, 14);
            @$senha_o_md5 = md5($senha_o);
        }

        if($array_userData['usuario_ativo'] == 0){
            echo "<script>finishAction('Este Usuário está Desativado! Entre em contato com a secretaria do SENAI para reativá-lo.', null, 0);</script>";
            die();
        } else if($array_select['senha'] === $senha_o_md5){
            echo "<script>finishAction('Você deve alterar sua senha para continuar!<br><br>Você será redirecionado em ', 'atualizar_senha.php?user=$login&pass=$senha&perfil=$perfil');</script>";
            die();
        }
        
        $empresa_id = $perfil === 'alunos' ? $array_userData["empresa_id"] : null;

        setcookie('login', implode(",", [$array_select["nome"], $array_userData["nome"], $array_userData["id"], $perfil, $empresa_id]));
        echo "<script>window.location.href='index.php'</script>";
        die();
    }
?>