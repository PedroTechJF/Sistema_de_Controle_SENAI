<?php
    require "help_files/db.php";
    @$login = $_COOKIE['login'];
    @$login_data = explode(",", $login);
    if (!isset($login)) {
        echo "<script>window.location.href = 'index.php'</script>";
    }

    $id_user = $login_data[2];
    $perfil_user = $login_data[3];
    $perfil_n = substr($perfil_user, 0, -1);
    $perfil_t = substr($perfil_user, 0, 3);
    $dados_user = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM $perfil_user WHERE id = '$id_user'"));
    $username = mysqli_fetch_row(mysqli_query($mysql, "SELECT login FROM usuarios_$perfil_user WHERE $perfil_n" . "_id = '$id_user'"))[0];
    if($perfil_user === 'alunos'){
        $escola_user = mysqli_fetch_row(mysqli_query($mysql, "SELECT nome FROM escolas WHERE id = " . $dados_user['escola_id']))[0];
        $empresa_aluno = mysqli_fetch_row(mysqli_query($mysql, "SELECT nome FROM empresas WHERE id = " . $dados_user['empresa_id']))[0];
    } else if($perfil_user === 'empresas'){
        $nomeReponsavel = mysqli_fetch_row(mysqli_query($mysql, "SELECT nome FROM usuarios_empresas WHERE empresa_id = '$id_user'"))['0'];
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados Do Usuário</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon"></head>
<body>
    <?php require "help_files/menu.php" ?>
    <div class="container">
        <div class="main_form">
            <h1>Dados Do Usuário</h1>
            <form action="#" method="get" class="form">
                <div class="page">
                    <h3>Dados Gerais<br></h3>
                    <?php 
                        if($perfil_user === 'alunos'){
                    ?>
                            <label for="nomeAluno">Nome Completo</label>
                            <input type="text" name="nomeAluno" id="nomeAluno" value="<?php echo ucwords(strtolower($dados_user['nome'])); ?>" readonly>
                            <label for="cpf">CPF</label>
                            <input type="text" name="cpf" id="cpf" value="<?php echo $dados_user['cpf']; ?>" readonly>
                            <label for="telefone">Telefone</label>
                            <input type="text" name="telefone" id="telefone" value="<?php echo $dados_user['telefone']; ?>" readonly>
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" value="<?php echo strtolower($dados_user['email']); ?>" readonly>
                            <label for="vinculo">Vínculo</label>
                            <input type="text" name="vinculo" id="vinculo" value="<?php echo ucfirst(strtolower($dados_user['vinculo'])); ?>" readonly>
                            <label for="escola">Escola</label>
                            <input type="text" name="escola" id="escola" value="<?php echo $escola_user; ?>" readonly>
                            <label for="empresa">Empresa</label>
                            <input type="text" name="empresa" id="empresa" value="<?php echo $empresa_aluno; ?>" readonly>
                    <?php
                        } else if($perfil_user === 'empresas'){
                    ?>
                            <label for="nomeEmpresa">Nome da Empresa</label>
                            <input type="text" name="nomeEmpresa" id="nomeEmpresa" value="<?php echo $dados_user['nome']; ?>" readonly>
                            <label for="nomeResponsavel">Nome Responsável</label>
                            <input type="text" name="nomeResponsavel" id="nomeResponsavel" value="<?php echo ucwords(strtolower($nomeReponsavel)); ?>" readonly>
                            <label for="cnpj">CNPJ</label>
                            <input type="text" name="cnpj" id="cnpj" value="<?php echo $dados_user['cnpj']; ?>" readonly>
                            <label for="telefone">Telefone</label>
                            <input type="text" name="telefone" id="telefone" value="<?php echo $dados_user['telefone']; ?>" readonly>
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" value="<?php echo $dados_user['email']; ?>" readonly>
                    <?php
                        } else if($perfil_user === 'escolas'){
                    ?>
                            <label for="nomeEscola">Nome da Escola</label>
                            <input type="text" name="nomeEscola" id="nomeEscola" value="<?php echo $dados_user['nome']; ?>" readonly>
                            <label for="cnpj">CNPJ</label>
                            <input type="text" name="cnpj" id="cnpj" value="<?php echo $dados_user['cnpj']; ?>" readonly>
                            <label for="telefone">Telefone</label>
                            <input type="text" name="telefone" id="telefone" value="<?php echo $dados_user['telefone']; ?>" readonly>
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" value="<?php echo $dados_user['email']; ?>" readonly>
                    <?php
                        }
                    ?>
                </div>
                <div class="page hidePage">
                    <h3>Endereço<br></h3>
                    <div class="campoEndereco" style="text-align: center; width: 185px">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep" value="<?php echo $dados_user['cep']; ?>" readonly>
                    </div>
                    <div class="enderecoArea">
                        <div class="campoEndereco">
                            <label for="logradouro">Logradouro</label>
                            <input type="text" name="logradouro" id="logradouro" value="<?php echo ucwords(strtolower($dados_user['logradouro'])); ?>" readonly>
                        </div>
                        <div class="campoEndereco">
                            <label for="num">Número</label>
                            <input type="text" name="num" id="num" value="<?php echo $dados_user['num']; ?>" readonly>
                        </div>
                        <div class="campoEndereco">
                            <label for="bairro">Bairro</label>
                            <input type="text" name="bairro" id="bairro" value="<?php echo ucwords(strtolower($dados_user['bairro'])); ?>" readonly>
                        </div>
                        <div class="campoEndereco">
                            <label for="complemento">Complemento</label>
                            <input type="text" name="complemento" id="complemento" value="<?php echo ucwords(strtolower($dados_user['complemento'])); ?>" readonly>
                        </div>
                        <div class="campoEndereco">
                            <label for="cidade">Cidade</label>
                            <input type="text" name="cidade" id="cidade" value="<?php echo ucwords(strtolower($dados_user['cidade'])); ?>" readonly>
                        </div>
                        <div class="campoEndereco">
                            <label for="estado">Estado</label>
                            <input type="text" name="estado" id="estado" value="<?php echo strtoupper($dados_user['estado']); ?>" readonly>
                        </div>                 
                    </div>
                </div>
                <div class="page hidePage">
                    <h3>Dados de Acesso</h3>
                    <label for="login">Usuário</label> 
                    <input type="text" name="login" id="login" value="<?php echo $username; ?>" readonly>
                </div>
                <div class="actionsBtn">
                    <input type="button" value="Anterior" class="actionBtn hideBtn" id="returnPage">
                    <input type="button" value="Próximo" class="actionBtn" id="nextPage">
                </div> 
            </form>
        </div>
        <div class="responseMsg"></div>
        <?php 
            if($perfil_user !== 'escolas'){
                echo '
                <div class="extraBtns bottomBtns">
                    <a href="atualizar.php?id=' . $id_user . '&type=' . $perfil_t . '" class="a_login">Atualizar Dados</a>
                </div>';
            }
        ?>
    </div>
    <script src="js/main.js"></script>
</body>
</html>