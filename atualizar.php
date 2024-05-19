<?php
    @$login = $_COOKIE['login'];
    @$login_data = explode(",", $login);
    @$id = $_GET['id'];
    @$type = $_GET['type'];
    if (!isset($login)) {
        echo "<script>window.location.href = 'login.php'</script>";
    } else if($login_data[3] === 'escolas'){
        echo "<script>window.location.href = 'atualizar_a.php?" . $_SERVER['QUERY_STRING'] . "'</script>";
    } else if ($login_data[3] !== 'escolas'){
        $pass = 0;
        if($login_data[3] === 'alunos' && $type === 'alu'){
            $pass = 1;
        } else if($login_data[3] === 'empresas' && $type === 'emp'){
            $pass = 1;
        }
        echo $pass === 0 ? "<script>window.location.href = 'erro.php?msg_id=1'</script>" : null;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização de Dados</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
    <script>
        function getMonth(year, month, day, elementId){
            const element = document.querySelector(`#${elementId}`)
            const o_monthStr = new Date(year, month, day).toLocaleString('pt-br', { month: 'long' })
            const monthStr = o_monthStr.slice(0, 1).toUpperCase() + o_monthStr.slice(1, o_monthStr.length)
            element.innerHTML = monthStr
        }
    </script>
</head>
<body>
    <?php 
        require "help_files/menu.php";
        require "help_files/db.php";
    ?>
    
    <div class="container">
        <div class="main_form">
            <?php
                if ($_SERVER["REQUEST_METHOD"] === "GET") {
            ?>
                <h1>Atualização de Dados</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?' . $_SERVER['QUERY_STRING'])?>" method="post" class="form" enctype="multipart/form-data">
                    <?php
                        if($type === 'emp') {
                            $query_select = "SELECT * FROM empresas WHERE id = '$id'";
                            $select_result = mysqli_query($mysql, $query_select);
                            $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);
                            $dados_usuario = mysqli_fetch_array(mysqli_query($mysql, "SELECT * FROM usuarios_empresas WHERE empresa_id = '$id'"));

                            $row = $array_select[0];
                            $o_nomeEmpresa = $row['nome'];
                            $o_nomeResponsavel = ucwords(strtolower($dados_usuario['nome']));
                            $o_cnpj = $row['cnpj'];
                            $o_telefone = $row['telefone'];
                            $o_email = strtolower($row['email']);
                            $o_cep = $row['cep'];
                            $o_logradouro = ucwords(strtolower($row['logradouro']));
                            $o_num = $row['num'];
                            $o_bairro = ucwords(strtolower($row['bairro']));
                            $o_complemento = ucwords(strtolower($row['complemento']));
                            $o_cidade = ucwords(strtolower($row['cidade']));
                            $o_estado = strtoupper($row['estado']);
                            $o_status = $row['usuario_ativo'];
                            $o_username = $dados_usuario['login'];
                    ?>
                            <div class="page">
                                <h3>Dados Gerais<br></h3>
                                <label for="nomeEmpresa">Nome da Empresa</label>
                                <input type="text" name="nomeEmpresa" id="nomeEmpresa" value="<?php echo $o_nomeEmpresa ?>">
                                <label for="nomeResponsavel">Nome Responsável</label>
                                <input type="text" name="nomeResponsavel" id="nomeResponsavel" value="<?php echo $o_nomeResponsavel ?>">
                                <label for="cnpj">CNPJ</label>
                                <input type="text" name="cnpj" id="cnpj" value="<?php echo $o_cnpj ?>" readonly class='readonly'>
                                <label for="telefone">Telefone</label>
                                <input type="text" name="telefone" id="telefone" value="<?php echo $o_telefone ?>" readonly class='readonly'>
                                <label for="email">E-mail</label>
                                <input type="email" name="email" id="email" value="<?php echo $o_email ?>" readonly class='readonly'>
                                <label for="status">Status da Empresa (Clique pra Alterar)</label>
                                <select name="status" id="status">
                                    <option value="<?php echo $o_status; ?>" selected hidden><?php echo $o_status == 1 ? "Ativa" : "Desativada"; ?></option>
                                    <option value="1">Ativa</option>
                                    <option value="0">Desativada</option>
                                </select>
                            </div>
                            <div class="page hidePage">
                                <h3>Endereço<br></h3>
                                <div class="campoEndereco" style="text-align: center; width: 185px">
                                    <label for="cep">CEP</label>
                                    <input type="text" name="cep" id="cep" value="<?php echo $o_cep ?>">
                                </div>
                                <div class="enderecoArea">
                                    <div class="campoEndereco">
                                        <label for="logradouro">Logradouro</label>
                                        <input type="text" name="logradouro" id="logradouro" value="<?php echo $o_logradouro ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="num">Número</label>
                                        <input type="text" name="num" id="num" value="<?php echo $o_num ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="bairro">Bairro</label>
                                        <input type="text" name="bairro" id="bairro" value="<?php echo $o_bairro ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="complemento">Complemento</label>
                                        <input type="text" name="complemento" id="complemento" value="<?php echo $o_complemento ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" name="cidade" id="cidade" value="<?php echo $o_cidade ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="estado">Estado</label>
                                        <input type="text" name="estado" id="estado" value="<?php echo $o_estado ?>">
                                    </div>                 
                                </div>
                            </div>
                            <div class="page hidePage">
                                <h3>Dados de Acesso</h3>
                                <label for="login">Usuário</label> 
                                <input type="text" name="login" id="login" value="<?php echo $o_username ?>" readonly class='readonly'>
                                <label for="">Senha</label>
                                <input type="text" style="cursor: pointer;" onclick="window.location.href = 'atualizar_senha.php?user=<?php echo $o_username; ?>&perfil=alunos'" value="Atualizar Senha">
                            </div>
                            <div class="actionsBtn">
                                <input type="button" value="Anterior" class="actionBtn hideBtn" id="returnPage">
                                <input type="button" value="Próximo" class="actionBtn" id="nextPage">
                                <input type="button" value="Atualizar" class="submitBtn hideBtn">
                            </div>
                    <?php
                        } else if($type === 'rel'){
                            $query_select = "SELECT * FROM relatorios WHERE id = '$id'";
                            $select_result = mysqli_query($mysql, $query_select);
                            $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);

                            $row = $array_select[0];
                            $o_mes = $row['mes'];
                            $o_ano = $row['ano'];
                            $o_empresa_id = $row['empresa_id'];
                            $o_local_arquivo = $row['local_arquivo'];
                            $o_dados_empresa = mysqli_fetch_array(mysqli_query($mysql, "SELECT * FROM empresas WHERE id = '$o_empresa_id'"));
                            $o_cnpj_empresa = $o_dados_empresa['cnpj'];
                    ?>
                            <label for="cnpj">CNPJ</label>
                            <input type="text" name="cnpj" id="cnpj" value="<?php echo $o_cnpj_empresa ?>" readonly class='readonly'>
                            <label for="mes">Mês (Clique para alterar)</label>
                            <select name="mes" id="mes_select">
                                <option id="o_mes" value="<?php echo $o_mes < 10 ? 0 . $o_mes : $o_mes ?>" selected hidden><script>getMonth(<?php echo $o_ano ?>, <?php echo $o_mes-1 ?>, 1, "o_mes")</script></option>
                                <option value="" disabled style="font-weight: bolder;">Selecione um Mês</option>
                                <option value="01">Janeiro</option>
                                <option value="02">Fevereiro</option>
                                <option value="03">Março</option>
                                <option value="04">Abril</option>
                                <option value="05">Maio</option>
                                <option value="06">Junho</option>
                                <option value="07">Julho</option>
                                <option value="08">Agosto</option>
                                <option value="09">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                            </select>
                            <label for="ano">Ano (Clique para alterar)</label>
                            <select name="ano" id="ano_select">
                                <option value="<?php echo $o_ano ?>" selected hidden><?php echo $o_ano ?></option>
                                <option value="" disabled style="font-weight: bolder;">Selecione um Ano</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                            </select>
                            <label for="">Arquivo <a href="<?php echo $o_local_arquivo ?>" download style="text-decoration: underline; margin: 2px 0;"><i class="fa-solid fa-download"></i></a></label>
                            <label for="relatorio_up">Enviar Arquivo</label>
                            <input type="file" name="relatorio" id="relatorio_up" accept="application/pdf">
                            <div class="btns">
                                <!-- <input type="button" value="Voltar" class="goBack" onclick="window.location.href = 'relatorios.php'"> -->
                                <input type="button" value="Atualizar" class="submitBtn">
                            </div>
                    <?php        
                        } else if($type === 'alu'){
                            $query_select = "SELECT * FROM alunos WHERE id = '$id'";
                            $select_result = mysqli_query($mysql, $query_select);
                            $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);

                            $row = $array_select[0];
                            $o_nome = ucwords(strtolower($row['nome']));
                            $o_cpf = $row['cpf'];
                            $o_telefone = $row['telefone'];
                            $o_email = strtolower($row['email']);
                            $o_vinculo = ucfirst(strtolower($row['vinculo']));
                            $o_status = $row['usuario_ativo'];
                            $o_dados_empresa = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM empresas WHERE id = ". $row['empresa_id']));
                            $o_empresa = $o_dados_empresa['nome'];
                            $o_empresa_id = $o_dados_empresa['id'];
                            $o_escola = mysqli_fetch_row(mysqli_query($mysql, "SELECT nome FROM escolas WHERE id = ". $row['escola_id']))[0];
                            $o_username = mysqli_fetch_row(mysqli_query($mysql, "SELECT login FROM usuarios_alunos WHERE aluno_id = '$id'"))[0];
                            
                            $o_cep = $row['cep'];
                            $o_logradouro = ucwords(strtolower($row['logradouro']));
                            $o_num = $row['num'];
                            $o_bairro = ucwords(strtolower($row['bairro']));
                            $o_complemento = ucwords(strtolower($row['complemento']));
                            $o_cidade = ucwords(strtolower($row['cidade']));
                            $o_estado = strtoupper($row['estado']);
                    ?>
                            <div class="page">
                                <h3>Dados Gerais<br></h3>
                                <label for="nomeAluno">Nome Completo</label>
                                <input type="text" name="nomeAluno" id="nomeAluno" value="<?php echo $o_nome ?>">
                                <label for="cpf">CPF</label>
                                <input type="text" name="cpf" id="cpf" value="<?php echo $o_cpf ?>" readonly class='readonly'>
                                <label for="telefone">Telefone</label>
                                <input type="text" name="telefone" id="telefone" value="<?php echo $o_telefone ?>" readonly class='readonly'>
                                <label for="email">E-mail</label>
                                <input type="email" name="email" id="email" value="<?php echo $o_email ?>" readonly class='readonly'>
                                <label for="vinculo">Vínculo (Clique para Alterar)</label>
                                <select name="vinculo" id="vinculo">
                                    <option value="<?php echo $o_vinculo; ?>" selected hidden><?php echo $o_vinculo ?></option>
                                    <option value="aluno">Aluno</option>
                                    <option value="ex-aluno">Ex-aluno</option>
                                </select>
                                <label for="escola">Escola</label>
                                <input type="text" name="escola" id="escola" value="<?php echo $o_escola ?>" readonly class='readonly'>
                                <label for="empresa">Empresa (Clique para Alterar)</label>
                                <select name="empresa" id="empresa">
                                    <option value="<?php echo $o_empresa_id; ?>" selected hidden><?php echo $o_empresa ?></option>
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
                                <label for="status">Status do Aluno (Clique pra Alterar)</label>
                                <select name="status" id="status">
                                    <option value="<?php echo $o_status; ?>" selected hidden><?php echo $o_status == 1 ? "Ativo" : "Desativado"; ?></option>
                                    <option value="1">Ativo</option>
                                    <option value="0">Desativado</option>
                                </select>
                            </div>
                            <div class="page hidePage">
                                <h3>Endereço<br></h3>
                                <div class="campoEndereco" style="text-align: center; width: 185px">
                                    <label for="cep">CEP</label>
                                    <input type="text" name="cep" id="cep" value="<?php echo $o_cep ?>">
                                </div>
                                <div class="enderecoArea">
                                    <div class="campoEndereco">
                                        <label for="logradouro">Logradouro</label>
                                        <input type="text" name="logradouro" id="logradouro" value="<?php echo $o_logradouro ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="num">Número</label>
                                        <input type="text" name="num" id="num" value="<?php echo $o_num ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="bairro">Bairro</label>
                                        <input type="text" name="bairro" id="bairro" value="<?php echo $o_bairro ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="complemento">Complemento</label>
                                        <input type="text" name="complemento" id="complemento" value="<?php echo $o_complemento ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" name="cidade" id="cidade" value="<?php echo $o_cidade ?>">
                                    </div>
                                    <div class="campoEndereco">
                                        <label for="estado">Estado</label>
                                        <input type="text" name="estado" id="estado" value="<?php echo $o_estado ?>">
                                    </div>                 
                                </div>
                            </div>
                            <div class="page hidePage">
                                <h3>Dados de Acesso</h3>
                                <label for="login">Usuário</label> 
                                <input type="text" name="login" id="login" value="<?php echo $o_username ?>" readonly class='readonly'>
                                <label for="">Senha</label>
                                <input type="text" style="cursor: pointer;" onclick="window.location.href = 'atualizar_senha.php?user=<?php echo $o_username; ?>&perfil=alunos'" value="Atualizar Senha">
                            </div>
                            <div class="actionsBtn">
                                <input type="button" value="Anterior" class="actionBtn hideBtn" id="returnPage">
                                <input type="button" value="Próximo" class="actionBtn" id="nextPage">
                                <input type="button" value="Atualizar" class="submitBtn hideBtn">
                            </div> 
                    <?php        
                        } else if($type === 'sol_doc'){
                            $dados_solicitacao = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM solicitacoes_documentos WHERE id = '$id'"));
                            // $curso_solicitacao = mysqli_fetch_row(mysqli_query($mysql, "SELECT nome FROM cursos WHERE id = '" . $dados_solicitacao['curso_id'] ."'"));
                            $valor_solicitacao = mysqli_fetch_row(mysqli_query($mysql, "SELECT valor_solicitacao FROM solicitacoes WHERE id = '" . $dados_solicitacao['solicitacao_id'] ."'"))[0];
                            $dados_aluno = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM alunos WHERE id = '". $dados_solicitacao['aluno_id'] ."'"));
                                if($dados_solicitacao['local_boleto'] === null && $valor_solicitacao > 0){
                    ?>
                                    <label for="">Anexe aqui o Boleto de Pagamento</label>
                                    <label for="boleto_up">Enviar Arquivo</label>
                                    <input type="file" name="boleto_file" id="boleto_up" accept="application/pdf">
                                    <script>document.querySelector('.form').enctype = 'multipart/form-data'</script>
                    <?php
                                }
                    ?>          
                            <input type="text" name="cpf" id="cpf" value="<?php echo $dados_aluno['cpf']; ?>" hidden readonly class='readonly'></input>
                            <input type="text" name="solicitacao_id" id="solicitacao_id" value="<?php echo $dados_solicitacao['solicitacao_id']; ?>" hidden readonly class='readonly'></input>
                            <label for="status">Status de Entrega</label>
                            <select style="width: 366px;" name="status" id="status">
                                <option value="" selected disabled hidden>Selecione um Status</option>
                                <option value="Pagamento Pendente">Pagamento Pendente</option>
                                <option value="Pagamento Negado">Pagamento Negado</option>
                                <option value="Pagamento Aprovado">Pagamento Aprovado</option>
                                <option value="Disponível para Retirada">Disponível para Retirada</option>
                            </select>
                            <div class="btns">
                                <!-- <input type="button" value="Voltar" class="goBack" onclick="window.location.href = 'solicitacoes.php?sol_id=<?php echo $dados_solicitacao['solicitacao_id']; ?>&sol_type=solicitacoes_documentos'"> -->
                                <input type="button" value="Atualizar" class="submitBtn">
                            </div>
                    <?php
                        } else if($type === 'sol_just'){
                            $dados_solicitacao = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM justificativas_faltas WHERE id = '$id'"));
                            // $curso_solicitacao = mysqli_fetch_row(mysqli_query($mysql, "SELECT nome FROM cursos WHERE id = '" . $dados_solicitacao['curso_id'] ."'"));
                            $dados_aluno = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM alunos WHERE id = '". $dados_solicitacao['aluno_id'] ."'"));
                    ?>
                            <input type="text" name="solicitacao_id" id="solicitacao_id" value="<?php echo $dados_solicitacao['solicitacao_id']; ?>" hidden readonly class='readonly'></input>
                            <label for="status">Status de Entrega</label>
                            <select style="width: 250px;" name="status" id="status">
                                <option value="" selected disabled hidden>Selecione um Status</option>
                                <option value="Justificativa Aprovada">Justificativa Aprovada</option>
                                <option value="Justificativa Negada">Justificativa Negada</option>
                            </select>
                            <div class="btns">
                                <!-- <input type="button" value="Voltar" class="goBack" onclick="window.location.href = 'solicitacoes.php'"> -->
                                <input type="button" value="Atualizar" class="submitBtn">
                            </div>
                <?php          
                        } else {
                            echo '
                            <script>
                                window.onload = () => {
                                    document.querySelector(".main_form").style.display = "none";
                                    responseMsgField.innerHTML = "Nenhum tipo de dado para atualizar foi definido!"
                                }
                            </script>';
                        }
                ?>
                </form>
        </div>
        <div class="btns">
            <input type="button" value="Voltar Página" class="goBack" onclick="window.history.back()">
        </div>
        <?php          
            }   
        ?>
        <div class="responseMsg"></div>
    </div>
    <script src="js/main.js"></script>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            date_default_timezone_set('America/Sao_Paulo');

            if($type === 'emp'){
                $nomeEmpresa = strtoupper($_POST['nomeEmpresa']);
                $nomeResponsavel = ucwords(strtolower($_POST['nomeResponsavel']));
                $cnpj = $_POST['cnpj'];
                $email = strtoupper($_POST['email']);
                $telefone = $_POST['telefone'];
                $status = $_POST['status'];
                
                $cep = $_POST['cep'];
                $logradouro = strtoupper($_POST['logradouro']);
                $num = $_POST['num'];
                $bairro = strtoupper($_POST['bairro']);
                $complemento = strtoupper($_POST['complemento']);
                $cidade = strtoupper($_POST['cidade']);
                $estado = strtoupper($_POST['estado']);

                $login = $_POST['login'];
    
                $query_updateEmpresa = "UPDATE empresas SET nome = \"$nomeEmpresa\", cnpj = \"$cnpj\", telefone = \"$telefone\", email = \"$email\", cep = \"$cep\", logradouro = \"$logradouro\", num = \"$num\", bairro = \"$bairro\", complemento = \"$complemento\", cidade = \"$cidade\", estado = \"$estado\", usuario_ativo = \"$status\" WHERE id = \"$id\"";
                $updateEmpresa_action = mysqli_query($mysql, $query_updateEmpresa);
    
                if ($updateEmpresa_action){

                    $query_updateUser = "UPDATE usuarios_empresas SET login = \"$login\", nome = \"$nomeResponsavel\" WHERE empresa_id = '$id'";
                    $updateUser_action = mysqli_query($mysql, $query_updateUser);

                    if($updateUser_action){
                        echo "<script>finishAction(`Atualização Realizada com Sucesso!<br><br>Usuário: $login<br><br><div class='btns'><input type='button' value='Listar Empresas' class='btnsChild' onclick='window.location.href = \"empresas.php\"'><input type='button' value='Enviar Relatório' class='btnsChild' onclick='window.location.href = \"enviar_relatorio.php?cnpj=$cnpj\"'></div>`, null, 0)</script>";
                    } else {
                        echo "<script>finishAction('Não foi possível realizar a atualização dos dados dessa empresa!<br><br>Você será redirecionado em ', 'empresas.php')</script>";
                    }
                } else {
                    echo "<script>finishAction('Não foi possível realizar a atualização dos dados dessa empresa!<br><br>Você será redirecionado em ', 'empresas.php')</script>";
                }
            } else if($type === 'rel'){
                $cnpj = $_POST['cnpj'];
                $mes = $_POST['mes'];
                $ano = $_POST['ano'];
                $relatorio = $_FILES['relatorio'];
                $data = date("Y-m-d");
                $local_arquivo_o = mysqli_fetch_array(mysqli_query($mysql, "SELECT local_arquivo FROM relatorios WHERE id = '$id'"))["local_arquivo"];

                $filename = 'frequencia-alunos_' . $mes . '-' . $ano . '.pdf';
                $local_arquivo = './relatorios/' . $cnpj . '/' . $filename;

                if(move_uploaded_file($relatorio["tmp_name"], $local_arquivo)){
                    $query_atualizarSolDoc = "UPDATE relatorios SET mes = \"$mes\", ano = \"$ano\", local_arquivo = \"$local_arquivo\", data_criacao = \"$data\" WHERE id = '$id'";
                    $atualizarRelatorio_action = mysqli_query($mysql, $query_atualizarRelatorio);
        
                    if ($atualizarRelatorio_action){
                        echo "<script>finishAction('Relatório Atualizado com Sucesso!<br><br>Você será redirecionado em  ', 'relatorios.php')</script>";
                    } else {
                        echo "<script>finishAction('Não foi possível realizar a atualização desse relatório!<br><br>Você será redirecionado em  ', 'relatorios.php')</script>";
                    }
                } else {
                    echo "<script>finishAction('Não foi possível realizar a atualização desse relatório!<br><br>Você será redirecionado em  ', 'relatorios.php')</script>";
                }
            } else if($type === 'alu') {
                $nome = strtoupper($_POST['nomeAluno']);
                $vinculo = strtoupper($_POST['vinculo']);
                $empresa_id = $_POST['empresa'];
                $status = $_POST['status'];
                
                $cep = $_POST['cep'];
                $logradouro = strtoupper($_POST['logradouro']);
                $num = $_POST['num'];
                $bairro = strtoupper($_POST['bairro']);
                $complemento = strtoupper($_POST['complemento']);
                $cidade = strtoupper($_POST['cidade']);
                $estado = strtoupper($_POST['estado']);

                $query_atualizarAluno = "UPDATE alunos SET nome = \"$nome\", vinculo = \"$vinculo\", usuario_ativo = \"$status\", empresa_id = \"$empresa_id\", cep = \"$cep\", logradouro = \"$logradouro\", num = \"$num\", bairro = \"$bairro\", complemento = \"$complemento\", cidade = \"$cidade\", estado = \"$estado\" WHERE id = '$id'";
                $atualizarAluno_action = mysqli_query($mysql, $query_atualizarAluno);

                if($atualizarAluno_action){
                    echo "<script>finishAction('Seus Dados foram Atualizados com Sucesso!<br><br>Você será redirecionado em ', 'usuario.php')</script>";
                } else {
                    echo "<script>finishAction('Não foi possível realizar a atualização dos seus dados!<br><br>Você será redirecionado em ', 'usuario.php')</script>";
                }
            } else if($type === 'sol_doc'){
                $cpf = $_POST['cpf'];
                $status = $_POST['status'];
                $solicitacao_id = $_POST['solicitacao_id'];
                $data_final = date('Y-m-d');
                $data_entrega = $status === "Disponível para Retirada" ? "'$data_final'" : "NULL";

                $query_atualizarSolDoc = "UPDATE solicitacoes_documentos SET status = \"$status\", data_entrega = $data_entrega WHERE solicitacao_id = '$solicitacao_id'";

                if(isset($_FILES['boleto_file'])){
                    $boleto_file = $_FILES['boleto_file'];
                    $nome_arquivo = "boleto_solicitacao_$id.pdf";
                    $local_boleto = "./documentos/$cpf/boletos/$nome_arquivo";
                    if(move_uploaded_file($boleto_file["tmp_name"], $local_boleto)){
                        $query_atualizarSolDoc = "UPDATE solicitacoes_documentos SET status = \"$status\", local_boleto = \"$local_boleto\", data_entrega = $data_entrega WHERE solicitacao_id = '$solicitacao_id'";
                    } else {
                        echo "<script>finishAction('Não foi possível realizar a atualização dessa solicitação!<br><br>Você será redirecionado em ', 'solicitacoes.php?sol_id=$solicitacao_id&sol_type=solicitacoes_documentos')</script>";
                        die();
                    }
                }

                $atualizarSolDoc_action = mysqli_query($mysql, $query_atualizarSolDoc);
                if ($atualizarSolDoc_action){
                    $solicitacoes = mysqli_fetch_all(mysqli_query($mysql, "SELECT status FROM solicitacoes_documentos WHERE id = '$solicitacao_id'"));
                    $statusIgual = 1;
                    foreach($solicitacoes as $solicitacao){
                        if($solicitacao['status'] !== $status){
                            $statusIgual = 0;
                        }
                    }
                    if($statusIgual === 1){
                        $query_atualizarSols = "UPDATE solicitacoes_documentos SET status = '$status', data_entrega = $data_entrega WHERE id = '$solicitacao_id'";
                        $atualizarSol_action = mysqli_query($mysql, $query_atualizarSols);

                        $query_atualizarSol = "UPDATE solicitacoes SET status = '$status', data_entrega = $data_entrega WHERE id = '$solicitacao_id'";
                        $atualizarSol_action = mysqli_query($mysql, $query_atualizarSol);
                    }
                    echo "<script>finishAction('Solicitação Atualizada com Sucesso!<br><br>Você será redirecionado em ', 'solicitacoes.php?sol_id=$solicitacao_id&sol_type=solicitacoes_documentos')</script>";
                } else {
                    echo "<script>finishAction('Não foi possível realizar a atualização dessa solicitação!<br><br>Você será redirecionado em ', 'solicitacoes.php?sol_id=$solicitacao_id&sol_type=solicitacoes_documentos')</script>";
                }
            } else if($type === 'sol_just'){
                $status = $_POST['status'];
                $solicitacao_id = $_POST['solicitacao_id'];
                $data_final = date('Y-m-d');
                $data_entrega = $status === "Disponível para Retirada" ? "'$data_final'" : "NULL";

                $query_atualizarSolJus = "UPDATE justificativas_faltas SET status = \"$status\" WHERE solicitacao_id = '$solicitacao_id'";
                $atualizarSolJus_action = mysqli_query($mysql, $query_atualizarSolJus);

                if ($atualizarSolJus_action){
                    $query_atualizarSol = "UPDATE solicitacoes SET status = '$status', data_entrega = $data_entrega WHERE id = '$solicitacao_id'";
                    $atualizarSol_action = mysqli_query($mysql, $query_atualizarSol);
                    echo "<script>finishAction('Solicitação Atualizada com Sucesso!<br><br>Você será redirecionado em ', 'solicitacoes.php?sol_id=$solicitacao_id&sol_type=justificativas_faltas')</script>";
                } else {
                    echo "<script>finishAction('Não foi possível realizar a atualização dessa solicitação!<br><br>Você será redirecionado em ', 'solicitacoes.php?sol_id=$solicitacao_id&sol_type=justificativas_faltas')</script>";
                }
            }
        }
    ?>
</body>
</html>