<?php
    require "help_files/db.php";

    @$login = $_COOKIE['login'];
    @$login_data = explode(",", $login);
    if (!isset($login)) {
        echo "<script>window.location.href = 'login.php'</script>";
    } else if ($login_data[3] !== 'alunos'){
        echo "<script>window.location.href = 'erro.php?msg_id=1'</script>";
    } else {
        $id_aluno = $login_data[2];
        $dados_aluno = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM alunos WHERE id = '$id_aluno'"));
        $vinculoAluno = $dados_aluno['vinculo'];
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação de Documentos</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
</head>
<body>
    <?php require "help_files/menu.php"; ?>
    <div class="container">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                // $solicitoes_aluno = mysqli_fetch_all(mysqli_query($mysql, "SELECT * FROM solicitacoes WHERE aluno_id = '$id_aluno' AND status = 'Em Análise' AND tipo_solicitacao = 'Solicitação de Documentos'"));
                // if(count($solicitoes_aluno) > 0){
                //     echo "<div class='responseMsg'></div>";
                //     return "<script>finishAction('Você já possui uma solicitação em aberto!<br><br> Você será redirecionado para a página inicial em ', 'index.php')</script>";
                // }
        ?>
            <div class="main_form">
                <h1>Solicitação de Documentos</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="form" enctype="multipart/form-data">
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" id="cpf" value="<?php echo $dados_aluno['cpf']; ?>" readonly class="readonly">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?php echo ucwords(strtolower($dados_aluno['nome'])); ?>" readonly class="readonly">
                    <label for="vinculo">Vínculo</label>
                    <input type="text" name="vinculo" id="vinculo" value="<?php echo ucfirst(strtolower($vinculoAluno)); ?>" readonly class="readonly">
                    <!-- <select name="vinculo" id="vinculo" style="width: 366px;">
                        <option value="" selected disabled hidden>Selecione seu Vínculo</option>
                        <option value="aluno">Aluno</option>
                        <option value="ex-aluno">Ex-aluno</option>
                    </select> -->
                    <div class="extraFields">
                        <label for="curso">Qual seu Curso?</label>
                        <select name="curso" id="curso">
                            <option value="" selected disabled hidden>Selecione um Curso</option>
                            <?php 
                                $cursos = mysqli_fetch_all(mysqli_query($mysql, "SELECT * FROM cursos"), MYSQLI_ASSOC);
                                if(count($cursos) > 0) {
                                    foreach($cursos as $curso){
                                        $id_curso = $curso['id'];
                                        $nome_curso = $curso['nome'];
                                        $modalidade_curso = $curso['modalidade'];
                                        echo "<option value='$id_curso'>$nome_curso - $modalidade_curso</option>";
                                    }
                                }
                            ?>
                        </select>
                        <div class="docs">
                            <label for="documento">Documentos Desejados</label>
                            <div class="doc">
                                <div class="docInput">
                                    <select name="documento[]" id="documento">
                                        <option value="" selected disabled hidden>Selecione um Documento</option>
                                        <?php
                                            // @$vinculoAluno = $_COOKIE['vinculoAluno'];
                                            $documentos = mysqli_fetch_all(mysqli_query($mysql, "SELECT * FROM documentos WHERE vinculo_aluno = '$vinculoAluno'"), MYSQLI_ASSOC);
                                            if(count($documentos) > 0) {
                                                foreach($documentos as $documento){
                                                    $id_doc = $documento['id'];
                                                    $nome_doc = $documento['nome'];
                                                    $valor_doc = $documento['valor'];
                                                    echo "<option value='$id_doc' valor_doc='$valor_doc'>$nome_doc</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <a href="#" class="controlBtns" id="addNewDoc"><i class="fa-solid fa-plus"></i></a>
                            </div>
                        </div>
                        <label for="valor">Valor da Solicitação</label>
                        <input type="text" id="valor" name="valor" value="0" readonly>
                    </div>
                    <label for="observacao">Observação</label>
                    <input type="text" name="observacao" id="observacao">
                    <input type="button" value="Enviar" class="submitBtn">
                </form>
                <div class="responseMsg"></div>
            </div>
        <?php
            } else {
                echo "<div class='responseMsg'></div>";
            }
        ?>
    </div>
    <script src="js/main.js"></script>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {    
        function sendError($local){
            // return $local;
            return "<script>finishAction('Não foi possível realizar a solicitação desse documento!<br>Local de Erro: $local<br><br>A página irá recarregar em ', window.location.pathname)</script>";
        }

        date_default_timezone_set('America/Sao_Paulo');

        $cpf = $_POST['cpf'];
        $curso_id = $_POST['curso'];
        $escola_id = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT escolas_id FROM cursos WHERE id = '$curso_id'"))['escolas_id'];
        $valor_solicitacao = $_POST['valor'];
        $data_inicial = date('Y-m-d');
        $data_entrega_estimada = date('Y-m-d', strtotime($data_inicial . "+ 3 days"));
        $observacao = $_POST['observacao'] !== "" ? $_POST['observacao'] : null;
        $status = 'Em Análise';
        $data_solicitacao = date('Y-m-d H:i:s.u');

        $query_select = "SELECT * FROM alunos WHERE cpf = '$cpf'";
        $select_result = mysqli_query($mysql, $query_select);

        if (!$select_result) {
            echo "<script>finishAction('Você não está cadastrado em nosso sistema!<br><br>A página irá recarregar em ', window.location.pathname);</script>";
            die();
        } else {
            $query_criarSolicitacao = "INSERT INTO solicitacoes (data_inicial, data_entrega_estimada, data_entrega, status, data_solicitacao, valor_solicitacao, tipo_solicitacao, db_child, aluno_id, escola_id, empresa_id) VALUES (\"$data_inicial\", \"$data_entrega_estimada\", NULL, \"$status\", \"$data_solicitacao\", \"$valor_solicitacao\", \"Solicitação de Documentos\", \"solicitacoes_documentos\", \"$id_aluno\", \"$escola_id\", \"1\")";
            $insert_criarSolicitacao = mysqli_query($mysql, $query_criarSolicitacao);

            if($insert_criarSolicitacao){
                $solicitacao_id = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT id FROM solicitacoes WHERE data_solicitacao = '$data_solicitacao'"))['id'];
                foreach($_POST['documento'] as $index => $documento_id){
                    if($documento_id !== ""){
                        $valor_doc = mysqli_fetch_row(mysqli_query($mysql, "SELECT valor FROM documentos WHERE id = '$documento_id'"))[0];
                        $query_criarSolicitacaoDocumento = "INSERT INTO solicitacoes_documentos (data_inicial, data_entrega_estimada, data_entrega, observacao, status, data_solicitacao, valor_solicitacao, solicitacao_id, documentos_id, aluno_id, curso_id, curso_escolas_id) VALUES (\"$data_inicial\", \"$data_entrega_estimada\", NULL, \"$observacao\", \"$status\", \"$data_solicitacao\", \"$valor_doc\", \"$solicitacao_id\", \"$documento_id\", \"$id_aluno\", \"$curso_id\", \"$escola_id\")";
                        $insert_criarSolicitacaoDocumento = mysqli_query($mysql, $query_criarSolicitacaoDocumento);

                        if(!$insert_criarSolicitacaoDocumento){
                            echo sendError("insert_criarSolicitacaoDocumento");
                        } else if($index+1 === count($_POST['documento'])){
                            echo "<script>finishAction('Solicitação de Documento Realizada com Sucesso!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                            die();
                        }
                    }
                }
            } else {
                echo sendError("insert_criarSolicitacao");
            }
        }    
    }
?>