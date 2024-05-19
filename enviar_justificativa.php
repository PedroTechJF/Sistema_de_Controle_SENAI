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
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envio de Justificativa de Falta</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
</head>
<body>
    <?php require "help_files/menu.php"; ?>
    <div class="container">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
        ?>
            <div class="main_form">
                <h1>Envio de Justificativa de Falta</h1>
                <form style="width: 366px;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="form" enctype="multipart/form-data">
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" id="cpf" value="<?php echo $dados_aluno['cpf']; ?>" readonly class="readonly">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?php echo ucwords(strtolower($dados_aluno['nome'])); ?>" readonly class="readonly">
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
                    <label for="empresa">Empresa Contratante, se houver</label>
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
                    <label for="data_inicial">Data Inicial da Falta</label>
                    <input type="date" name="data_inicial" id="data_inicial" onclick="const today = new Date(); document.querySelector('#'+this.id).setAttribute('max',`${today.getFullYear()}-${today.getMonth()+1 < 10 ? `0${today.getMonth()+1}` : today.getMonth()+1}-${today.getDate() < 10 ? `0${today.getDate()}` : today.getDate()}`)">
                    <label for="dias">Tempo da Falta em Dias</label>
                    <input type="text" name="dias" id="dias" placeholder="Ex: 1">
                    <label for="justificativa">Qual sua Justificativa?</label>
                    <select name="justificativa" id="justificativa">
                        <option value="" selected disabled hidden>Selecione uma Justificativa</option>
                        <option value="Entrada em atraso">Entrada em atraso</option>
                        <option value="Saida antecipada">Saída antecipada</option>
                        <option value="1 dia ou mais de falta">1 dia ou mais de falta</option>
                    </select>
                    <label for="">Anexe aqui sua justificativa</label>
                    <label for="justificativa_up">Enviar Arquivo</label>
                    <input type="file" name="justificativa_file" id="justificativa_up">
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
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {    
            function sendError($local){
                // return $local;
                return "<script>finishAction('Não foi possível realizar o envio dessa Justificativa de Falta!<br>Local de Erro: $local<br><br>A página irá recarregar em ', window.location.pathname)</script>";
            }

            date_default_timezone_set('America/Sao_Paulo');

            $cpf = $_POST['cpf'];
            $curso_id = $_POST['curso'];
            $empresa_id = $_POST['empresa'];
            $escola_id = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT escolas_id FROM cursos WHERE id = '$curso_id'"))['escolas_id'];
            $justificativa = $_POST['justificativa'];
            $justificativa_file = $_FILES['justificativa_file'];
            $observacao = $_POST['observacao'] !== "" ? $_POST['observacao'] : null;
            $status = 'Em Análise';

            $data_solicitacao = date('Y-m-d H:i:s.u');
            $dias = $_POST['dias'];
            $data_inicial = date('Y-m-d', strtotime($_POST['data_inicial']));
            $data_final = date('Y-m-d', strtotime($data_inicial . "+ $dias days"));
            $data_entrega_estimada = date('Y-m-d', strtotime($data_solicitacao . "+ 3 days"));

            $query_select = "SELECT * FROM alunos WHERE cpf = '$cpf'";
            $select_result = mysqli_query($mysql, $query_select);

            if (!$select_result) {
                echo "<script>finishAction('Você não está cadastrado em nosso sistema!<br><br>A página irá recarregar em ', window.location.pathname);</script>";
                die();
            } else {
                $fileExt = strtolower(pathinfo(basename($justificativa_file["name"]),PATHINFO_EXTENSION));
                $filename = "Justificativa De Falta ($justificativa) - De $data_inicial até $data_final.$fileExt";
                $local_arquivo = "./documentos/$cpf/justificativas_faltas/$filename";
                if (file_exists($local_arquivo)) {
                    $id_justificativa = mysqli_fetch_array(mysqli_query($mysql, "SELECT id FROM justificativas_faltas WHERE local_arquivo = '$local_arquivo'"))['id'];
                    echo "<script>finishAction('Uma Justificativa de Falta com essas informações foi encontrada!<br><br>Redirecionando para página de atualização de dados em ', 'atualizar.php?id=" . $id_justificativa . "&type=jus');</script>";
                    die();
                }

                $query_criarSolicitacao = "INSERT INTO solicitacoes (data_inicial, data_entrega_estimada, data_entrega, status, data_solicitacao, tipo_solicitacao, db_child, aluno_id, escola_id, empresa_id) VALUES (\"$data_inicial\", \"$data_entrega_estimada\", NULL, \"$status\", \"$data_solicitacao\", \"Justificativa de Falta\", \"justificativas_faltas\", \"$id_aluno\", \"$escola_id\", \"$empresa_id\")";
                $insert_criarSolicitacao = mysqli_query($mysql, $query_criarSolicitacao);

                if($insert_criarSolicitacao){
                    $solicitacao_id = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT id FROM solicitacoes WHERE data_solicitacao = '$data_solicitacao'"))['id'];
                    $query_criarJustificativa = "INSERT INTO justificativas_faltas (dias, data_inicial, data_final, justificativa, observacao, status, local_arquivo, data_solicitacao, solicitacao_id, aluno_id, curso_id, curso_escolas_id, empresa_id) VALUES (\"$dias\", \"$data_inicial\", \"$data_final\", \"$justificativa\", \"$observacao\", \"$status\", \"$local_arquivo\", \"$data_solicitacao\", \"$solicitacao_id\", \"$id_aluno\", \"$curso_id\", \"$escola_id\", \"$empresa_id\")";
                    $insert_criarJustificativa = mysqli_query($mysql, $query_criarJustificativa);

                    if($insert_criarJustificativa){
                        if(move_uploaded_file($justificativa_file["tmp_name"], $local_arquivo)){
                            echo "<script>finishAction('Justificativa de Falta Enviada com Sucesso!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                        } else {
                            echo $curso_id;
                            echo sendError("upload_file");
                        }
                    } else {
                        echo sendError("insert_criarJustificativa");
                    }
                } else {
                    echo sendError("insert_criarSolicitacao");
                }
            }    
        }
    ?>
</body>
</html>