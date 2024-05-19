<?php
    require "help_files/db.php";
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
    <title>Cadastre-se</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon"></head>
<body>
<?php require "help_files/menu.php" ?>
    <div class="container">
        <?php 
            if($_SERVER["REQUEST_METHOD"] == "GET"){
        ?>
        <div class="main_form">
            <h1>Cadastre-se</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form">
                <div class="page">
                    <h3>Dados Gerais<br></h3>
                    <label for="nomeAluno">Nome Completo</label>
                    <input type="text" name="nomeAluno" id="nomeAluno">
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" id="cpf">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" id="telefone">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email">
                    <label for="vinculo">Vínculo</label>
                    <select name="vinculo" id="vinculo">
                        <option value="" selected disabled hidden>Selecione seu Vínculo</option>
                        <option value="aluno">Aluno</option>
                        <option value="ex-aluno">Ex-aluno</option>
                    </select>
                    <label for="escola">Escola</label>
                    <select name="escola" id="escola">
                        <option value="" selected disabled hidden>Selecione uma Escola</option>
                        <?php 
                            $escolas = mysqli_fetch_all(mysqli_query($mysql, "SELECT id, nome FROM escolas"), MYSQLI_ASSOC);
                            if(count($escolas) > 0) {
                                foreach($escolas as $escola){
                                    $id_esc = $escola['id'];
                                    $nome_esc = $escola['nome'];
                                    echo "<option value='$id_esc'>$nome_esc</option>";
                                }
                            }
                        ?>
                    </select>
                    <label for="empresa">Empresa</label>
                    <select name="empresa" id="empresa">
                        <option value="" selected disabled hidden>Selecione uma Empresa</option>
                        <?php 
                            $empresas = mysqli_fetch_all(mysqli_query($mysql, "SELECT id, nome FROM empresas"), MYSQLI_ASSOC);
                            if(count($empresas) > 0) {
                                foreach($empresas as $empresa){
                                    $id_emp = $empresa['id'];
                                    $nome_emp = $empresa['nome'];
                                    echo "<option value='$id_emp'>$nome_emp</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="page hidePage">
                    <h3>Endereço<br></h3>
                    <div class="campoEndereco" style="text-align: center; width: 185px">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep">
                    </div>
                    <div class="enderecoArea">
                        <div class="campoEndereco">
                            <label for="logradouro">Logradouro</label>
                            <input type="text" name="logradouro" id="logradouro">
                        </div>
                        <div class="campoEndereco">
                            <label for="num">Número</label>
                            <input type="text" name="num" id="num">
                        </div>
                        <div class="campoEndereco">
                            <label for="bairro">Bairro</label>
                            <input type="text" name="bairro" id="bairro">
                        </div>
                        <div class="campoEndereco">
                            <label for="complemento">Complemento</label>
                            <input type="text" name="complemento" id="complemento">
                        </div>
                        <div class="campoEndereco">
                            <label for="cidade">Cidade</label>
                            <input type="text" name="cidade" id="cidade">
                        </div>
                        <div class="campoEndereco">
                            <label for="estado">Estado</label>
                            <input type="text" name="estado" id="estado">
                        </div>                 
                    </div>
                </div>
                <div class="page hidePage">
                    <h3>Dados de Acesso</h3>
                    <label for="login">Usuário</label> 
                    <input type="text" name="login" id="login">
                    <label for="nova_senha">Senha</label> 
                    <div class="pass" style="width: -webkit-fill-available;">
                        <!-- <input type="password" name="senha" id="senha"> -->
                        <input type="password" name="senha" id="nova_senha">
                        <i class="controlPass fa-solid fa-eye" id="showPass"></i>
                        <i class="controlPass fa-solid fa-eye-slash" id="hidePass" style="display: none;"></i>
                    </div>
                </div>
                <div class="actionsBtn">
                    <input type="button" value="Anterior" class="actionBtn hideBtn" id="returnPage">
                    <input type="button" value="Próximo" class="actionBtn" id="nextPage">
                    <input type="button" value="Finalizar Cadastro" class="submitBtn hideBtn">
                </div> 
            </form>
        </div>
        <?php 
        }
        ?>
        <div class="responseMsg"></div>
        <div class="btns">
            <input type="button" value="Voltar Página" class="goBack" onclick="window.history.back()">
        </div>
    </div>
    <script src="js/main.js"></script>
    <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            date_default_timezone_set('America/Sao_Paulo');

            $nomeAluno = strtoupper($_POST['nomeAluno']);
            $cpf = $_POST['cpf'];
            $telefone = $_POST['telefone'];
            $email = strtoupper($_POST['email']);
            $vinculo = strtoupper($_POST['vinculo']);
            $escola_id = $_POST['escola'];
            $empresa_id = $_POST['empresa'];
            
            $cep = $_POST['cep'];
            $logradouro = strtoupper($_POST['logradouro']);
            $num = $_POST['num'];
            $bairro = strtoupper($_POST['bairro']);
            $complemento = strtoupper($_POST['complemento']);
            $cidade = strtoupper($_POST['cidade']);
            $estado = strtoupper($_POST['estado']);

            $login = $_POST['login'];
            $senha_o = $_POST['senha'];
            $senha = md5($senha_o);

            $query_select = "SELECT * FROM alunos";
            $select_result = mysqli_query($mysql, $query_select);
            $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);

            if(count($array_select) >= 0){
                for($i = 0; $i < count($array_select); $i++){
                    $row = $array_select[$i];
                    $id_aluno = $row['id'];
                    $username = mysqli_fetch_row(mysqli_query($mysql, "SELECT login FROM usuarios_alunos WHERE aluno_id = '$id_aluno'"));
                    if ($row["cpf"] === $cpf) {
                        echo "<script>finishAction('Já existe um usuário com esse CPF cadastrado!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                        die();
                    } else if($row["email"] === $email){
                        echo "<script>finishAction('Já existe um usuário com esse e-mail cadastrado!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                        die();
                    } else if($row["telefone"] === $telefone){
                        echo "<script>finishAction('Já existe um usuário com esse telefone cadastrado!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                        die();
                    } else if($username === $login){
                        echo "<script>finishAction('Já existe um usuário com esse login cadastrado!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                        die();
                    }
                }
                $query_criarAluno = "INSERT INTO alunos (nome, cpf, telefone, email, cep, logradouro, num, bairro, complemento, cidade, estado, vinculo, usuario_ativo, escola_id, empresa_id) VALUES (\"$nomeAluno\", \"$cpf\", \"$telefone\", \"$email\", \"$cep\", \"$logradouro\", \"$num\", \"$bairro\", \"$complemento\", \"$cidade\", \"$estado\", \"$vinculo\", 1, \"$escola_id\", \"$empresa_id\")";
                $insert_criarAluno = mysqli_query($mysql, $query_criarAluno);
    
                if ($insert_criarAluno){
                    $aluno_id = mysqli_fetch_array(mysqli_query($mysql, "SELECT id FROM alunos WHERE cpf = '$cpf'"))['id'];      
                    $query_criarUsuario = "INSERT INTO usuarios_alunos (login, senha, nome, aluno_id, escola_id, empresa_id) VALUES (\"$login\", \"$senha\", \"$nomeAluno\", \"$aluno_id\", \"$escola_id\", \"$empresa_id\")";
                    $insert_criarUsuario = mysqli_query($mysql, $query_criarUsuario);
                    
                    if($query_criarUsuario){
                        $dir = "./documentos/$cpf";
                        if(!is_dir($dir)){
                            mkdir($dir);
                            if(is_dir($dir)){
                                mkdir($dir.'/justificativas_faltas');
                                mkdir($dir.'/boletos');
                            }
                        }
                        echo "<script>finishAction('Cadastro Realizado com Sucesso!<br><br>Você será redirecionado para a página de login em ', 'login.php')</script>";
                    } else {
                        echo "<script>finishAction('Não foi possível realizar o seu cadastro!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                    }
                } else {
                    echo "<script>finishAction('Não foi possível realizar o seu cadastro!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                }
            } else {
                echo "<script>finishAction('Não foi possível realizar o seu cadastro!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
            }
        }
    ?>
</body>
</html>