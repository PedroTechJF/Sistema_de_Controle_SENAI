<?php
    @$login = $_COOKIE['login'];
    @$login_data = explode(",", $login);
    if (!isset($login)) {
        echo "<script>window.location.href = 'login.php'</script>";
    } else if ($login_data[3] !== 'escolas'){
        echo "<script>window.location.href = 'erro.php?msg_id=1'</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empresas</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon"></head>

<body>
    <?php require "help_files/menu.php" ?>
    <div class="container">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
        ?>
                <div class="main_form">
                    <h1>Cadastro de Empresa</h1>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="form">
                        <h3>Dados Gerais<br></h3>
                        <label for="nomeEmpresa">Nome da Empresa</label>
                        <input type="text" name="nomeEmpresa" id="nomeEmpresa">
                        <label for="nomeResponsavel">Nome Responsável</label>
                        <input type="text" name="nomeResponsavel" id="nomeResponsavel">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj">
                        <label for="telefone">Telefone</label>
                        <input type="text" name="telefone" id="telefone">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email">
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
                        <input type="button" value="Cadastrar" class="submitBtn">
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

            $nomeEmpresa = strtoupper($_POST['nomeEmpresa']);
            $nomeResponsavel = $_POST['nomeResponsavel'];
            $cnpj = $_POST['cnpj'];
            $email = strtoupper($_POST['email']);
            $telefone = $_POST['telefone'];
            
            $cep = $_POST['cep'];
            $logradouro = strtoupper($_POST['logradouro']);
            $num = $_POST['num'];
            $bairro = strtoupper($_POST['bairro']);
            $complemento = strtoupper($_POST['complemento']);
            $cidade = strtoupper($_POST['cidade']);
            $estado = strtoupper($_POST['estado']);

            $login = explode('@', $email)[0] . "_" . substr($cnpj, 0, 4);
            $senha_o = explode('.', explode('@', $email)[1])[0] . "_" . substr($cnpj, 10, 14);
            $senha = md5($senha_o);

            $query_select = "SELECT * FROM empresas";
            $select_result = mysqli_query($mysql, $query_select);
            $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);

            if(count($array_select) >= 0){
                for($i = 0; $i < count($array_select); $i++){
                    $row = $array_select[$i];
                    if ($row["cnpj"] === $cnpj || $row["email"] === $email || $row["telefone"] === $telefone) {
                        echo "<script>finishAction('Esta empresa já está cadastrada!<br><br>A página irá recarregar em ', window.location.pathname);</script>";
                        die();
                    }
                }
                $query_criarEmpresa = "INSERT INTO empresas (nome, cnpj, telefone, email, cep, logradouro, num, bairro, complemento, cidade, estado, usuario_ativo) VALUES (\"$nomeEmpresa\", \"$cnpj\", \"$telefone\", \"$email\", \"$cep\", \"$logradouro\", \"$num\", \"$bairro\", \"$complemento\", \"$cidade\", \"$estado\", 1)";
                $insert_criarEmpresa = mysqli_query($mysql, $query_criarEmpresa);
    
                if ($insert_criarEmpresa){
                    $empresa_id = mysqli_fetch_array(mysqli_query($mysql, "SELECT id FROM empresas WHERE cnpj = '$cnpj'"))['id'];      
                    $query_criarUsuario = "INSERT INTO usuarios_empresas (login, senha, nome, empresa_id) VALUES (\"$login\", \"$senha\", \"$nomeResponsavel\", \"$empresa_id\")";
                    $insert_criarUsuario = mysqli_query($mysql, $query_criarUsuario);
                    
                    if($query_criarUsuario){
                        $dir = "./relatorios/" . $cnpj;
                        if(!is_dir($dir)){
                            mkdir($dir);
                        }
                        echo "<script>finishAction(`Cadastro Realizado com Sucesso!<br><br>Usuário: $login<br>Senha: $senha_o<br><br><div class='btns'><input type='button' value='Cadastrar Empresa' class='btnsChild' onclick='window.location.href = \"cadastro_empresa.php\"'><input type='button' value='Listar Empresas' class='btnsChild' onclick='window.location.href = \"empresas.php\"'><input type='button' value='Enviar Relatório' class='btnsChild' onclick='window.location.href = \"enviar_relatorio.php?cnpj=$cnpj\"'></div>`, null, 0)</script>";
                    }
                } else {
                    echo "<script>finishAction('Não foi possível realizar o cadastro dessa empresa!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                }
            }
        }
    ?>
</body>
</html>