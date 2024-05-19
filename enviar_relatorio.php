<?php
    $login = $_COOKIE['login'];
    $login_data = explode(",", $login);
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
    <title>Envio de Relatórios</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon"></head>
<body>
    <?php require "help_files/menu.php"; ?>
    <div class="container">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
        ?>
            <div class="main_form">
                <h1>Envio de Relatórios</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="form" enctype="multipart/form-data">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" name="cnpj" id="cnpj" value="<?php if(isset($_GET['cnpj'])){ echo $_GET['cnpj']; } else { echo ''; } ?>">
                    <label for="mes">Mês</label>
                    <select name="mes" id="mes_select">
                        <option value="" selected disabled hidden>Selecione um Mês</option>
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
                    <label for="ano">Ano</label>
                    <select name="ano" id="ano_select">
                        <option value="" selected disabled hidden>Selecione um Ano</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                    </select>
                    <label for="">Arquivo</label>
                    <label for="relatorio_up">Enviar Arquivo</label>
                    <input type="file" name="relatorio" id="relatorio_up" accept="application/pdf">
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
            require "help_files/db.php";
            
            date_default_timezone_set('America/Sao_Paulo');

            $cnpj = $_POST['cnpj'];
            $mes = $_POST['mes'];
            $ano = $_POST['ano'];
            $relatorio = $_FILES['relatorio'];
            $data = date("Y-m-d");

            $query_select = "SELECT * FROM empresas WHERE cnpj = '$cnpj'";
            $select_result = mysqli_query($mysql, $query_select);
            $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);

            if (!$select_result || count($array_select) <= 0) {
                echo "<script>finishAction('Esta empresa não está cadastrada!<br><br>A página irá recarregar em ', window.location.pathname);</script>";
                die();
            } else {
                $filename = 'frequencia-alunos_' . $mes . '-' . $ano . '.pdf';
                $local_arquivo = './relatorios/' . $cnpj . '/' . $filename;
                if (file_exists($local_arquivo)) {
                    $id_relatorio = mysqli_fetch_array(mysqli_query($mysql, "SELECT id FROM relatorios WHERE local_arquivo = '$local_arquivo'"))['id'];
                    echo "<script>finishAction('Um relatório com essas informações foi encontrado!<br><br>Redirecionando para página de atualização de dados em ', 'atualizar.php?id=" . $id_relatorio . "&type=rel');</script>";
                    die();
                }
                if(move_uploaded_file($relatorio["tmp_name"], $local_arquivo)){
                    $empresa_id = mysqli_fetch_array(mysqli_query($mysql, "SELECT id FROM empresas WHERE cnpj = '$cnpj'"))['id'];
                    $query_criarRelatorio = "INSERT INTO relatorios (mes, ano, local_arquivo, data_criacao, empresa_id) VALUES (\"$mes\", \"$ano\", \"$local_arquivo\", \"$data\", \"$empresa_id\")";
                    $insert_criarRelatorio = mysqli_query($mysql, $query_criarRelatorio);
        
                    if ($insert_criarRelatorio){
                        echo "<script>finishAction('Relatório Enviado com Sucesso!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                    } else {
                        echo "<script>finishAction('Não foi possível realizar o envio desse relatório!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                    }
                } else {
                    echo "<script>finishAction('Não foi possível realizar o envio desse relatório!<br><br>A página irá recarregar em ', window.location.pathname)</script>";
                }
            }
        }
    ?>
</body>
</html>