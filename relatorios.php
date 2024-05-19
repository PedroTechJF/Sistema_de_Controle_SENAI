<?php
    if (!isset($_COOKIE['login'])) {
        echo "<script>window.location.href = 'login.php'</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Relatórios</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
<body>
    <?php require "help_files/menu.php" ?>
    <script>
        const searchDiv = document.querySelector('#searchDiv');
        let children = []
        for(child of searchDiv.childNodes){
            child.accessKey !== undefined ? children.push(child) : null
        }

        searchDiv.innerHTML = '';
        searchDiv.appendChild(children[0])
        searchDiv.innerHTML += `<?php require 'help_files/pesquisar_area.php' ?>`
        searchDiv.appendChild(children[1])
        document.querySelector('#pesquisaTp').value = 'relatorios'
    </script>
    <div class="container">
        <?php 
            require "help_files/db.php";
            $login = $_COOKIE['login'];
            $login_data = explode(",", $login);
            if($login_data[3] !== 'escolas'){
                $empresa_id = $login_data[2];
                $query_select = "SELECT * FROM relatorios WHERE empresa_id = '$empresa_id'";
            } else {
                @$id = $_GET['id'];
                if(isset($id)){
                    $query_select = "SELECT * FROM relatorios WHERE empresa_id = '$id'";
                } else {
                    $query_select = "SELECT * FROM relatorios";
                }
            }
            $select_result = mysqli_query($mysql, $query_select);
            $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);

            if(count($array_select) > 0){
        ?>
                <table class="mainTable" cellspacing="0">
                    <thead class="tableHead">
                        <tr>
                            <th>ID</th>
                            <th>Mês</th>
                            <th>Ano</th>
                            <?php 
                                if($login_data[3] !== 'empresas') {
                                    echo '<th>Data de Criação</th>';
                                }
                            ?>
                            <th>Empresa</th>
                            <th>Download</th>
                            <?php 
                                if($login_data[3] !== 'empresas') {
                                    echo '<th>Editar</th><th>Excluir</th>';
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for($i = 0; $i < count($array_select); $i++){
                                $row = $array_select[$i];
                                $id = $row['id'];
                                $mes = $row['mes'];
                                $ano = $row['ano'];
                                $local_arquivo = $row['local_arquivo'];
                                $nome_arquivo = substr($local_arquivo, strrpos($local_arquivo, '/') + 1);
                                $data_criacao = date('d/m/Y', strtotime($row['data_criacao']));
                                $empresa_id = $row['empresa_id'];
                                $dados_empresa = mysqli_fetch_array(mysqli_query($mysql, "SELECT * FROM empresas WHERE id = '$empresa_id'"));
                                $nome_empresa = $dados_empresa['nome'];

                                $months = [
                                    'Janeiro',
                                    'Fevereiro',
                                    'Março',
                                    'Abril',
                                    'Maio',
                                    'Junho',
                                    'Julho',
                                    'Agosto',
                                    'Setembro',
                                    'Outubro',
                                    'Novembro',
                                    'Dezembro'
                                ]
                        ?>
                            <tr>
                                <td><?php echo $id; ?></td>
                                <td><?php echo $months[$mes-1]; ?></td>
                                <td><?php echo $ano; ?></td>
                                <?php 
                                    if($login_data[3] !== 'empresas') {
                                        echo '<td>' . $data_criacao . '</td>
                                        <td><a style="text-decoration: underline;" href="empresas.php?id=' . $empresa_id . '">' . $nome_empresa . '</a></td>
                                        <td class="download"><a href="' . $local_arquivo . '" download><i class="fa-solid fa-download"></i></a></td>
                                        <td class="editRow"><a href="atualizar.php?id=' . $id . '&type=rel"><i class="fa-solid fa-pen"></i></a></td>
                                        <td class="deleteRow"><a href="deletar.php?id=' . $id . '&type=rel"><i class="fa-solid fa-trash"></i></a></td>';
                                    } else {
                                        echo '<td>' . $nome_empresa . '</td>
                                        <td class="download"><a href="' . $local_arquivo . '" download><i class="fa-solid fa-download"></i></a></td>';
                                    }
                                ?>
                            </tr>
                        <?php 
                            }
                        ?>
                    </tbody>
                </table>
            <?php 
            } else {
                echo "<p>Não Há Nenhum Relátorio Enviado!</p>";
            }
            mysqli_close($mysql);
            ?>
    </div>
    <script src="js/main.js"></script>
</body>
</html>