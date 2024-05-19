<?php
    $login = $_COOKIE['login'];
    $login_data = explode(",", $login);
    if (!isset($login)) {
        echo "<script>window.location.href = 'login.php'</script>";
    }
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        echo "<script>window.location.href = 'erro.php?msg_id=2'</script>";
    }

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
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Empresas</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon"></head>
    <script>
        function formatElement(data, type){
            const tableBody = document.querySelector('.tableBody')
            tableBody.children[tableBody.children.length-1].innerHTML += `<td>${formatText(data, type)}</td>`
        }
    </script>
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
    </script>
    <script src="js/main.js"></script>
    <div class="container">
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                require "help_files/db.php";

                $search = $_POST['search'];
                $pesquisaTp = $_POST['pesquisaTp'];
                echo '<script>document.querySelector("#pesquisaTp").value = "' . $pesquisaTp . '"</script>';

                if($search === ""){
                    echo "O campo de pesquisa não deve estar vazio!";
                    exit();
                }

                if($pesquisaTp === 'empresas'){
                    $query_select = "SELECT * FROM empresas WHERE id = '$search' OR nome LIKE '%$search%' OR cnpj = '$search' OR email = '$search' OR telefone = '$search' OR cep = '$search' OR logradouro = '$search' OR bairro = '$search' OR cidade = '$search' OR estado = '$search'";
                    $select_result = mysqli_query($mysql, $query_select);
                    $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);

                    if(count($array_select) > 0) {
        ?>
                        <table class="mainTable" cellspacing="0">
                            <thead class="tableHead">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>CNPJ</th>
                                    <th>Telefone</th>
                                    <th>E-Mail</th>
                                    <th>Localização</th>
                                    <th>Relatórios</th>
                                    <th>Upload</th>
                                    <th>Editar</th>
                                    <th>Excluir</th>
                                </tr>
                            </thead>
                            <tbody class="tableBody">
                                <?php
                                    for($i = 0; $i < count($array_select); $i++){
                                        $row = $array_select[$i];
                                        $id = $row['id'];
                                        $nome = $row['nome'];
                                        $cnpj = $row['cnpj'];
                                        $email = strtolower($row['email']);
                                        $telefone = $row['telefone'];

                                        $cep = $row['cep'];
                                        $logradouro = strtoupper($row['logradouro']);
                                        $num = $row['num'];
                                        $bairro = strtoupper($row['bairro']);
                                        $complemento = strtoupper($row['complemento']);
                                        $cidade = strtoupper($row['cidade']);
                                        $estado = strtoupper($row['estado']);
                                        $endereco = "$logradouro, $num - $bairro, $cidade - $estado, $cep";
                                ?>
                                    <tr>
                                        <td><?php echo $id; ?></td>
                                        <td><?php echo $nome; ?></td>
                                        <?php
                                            echo "<script>formatElement('$cnpj', 'cnpj')</script>";
                                            $type = strlen($telefone) <= 10 ? 'telefone_fixo' : 'telefone';
                                            echo "<script>formatElement('$telefone', '$type')</script>" 
                                        ?>
                                        <td><?php echo $email; ?></td>
                                        <td><a href="https://www.google.com/maps/search/<?php echo $endereco ?>" target="_blank"><i class="fa-solid fa-map-location-dot"></i></a></td>
                                        <td><a href="relatorios.php?id=<?php echo $id ?>"><i class="fa-solid fa-folder-open"></i></a></td>
                                        <td><a href="enviar_relatorio.php?cnpj=<?php echo $cnpj ?>"><i class="fa-solid fa-upload"></i></a></td>
                                        <td class="editRow"><a href="atualizar.php?id=<?php echo $id ?>&type=emp"><i class="fa-solid fa-pen"></i></a></td>
                                        <td class="deleteRow"><a href="deletar.php?id=<?php echo $id ?>&type=emp"><i class="fa-solid fa-trash"></i></a></td>
                                    </tr>

                                <?php 
                                    }
                                ?>
                            </tbody>
                        </table>
        <?php
                    } else {
                        echo "<p>Não foi encontrado nenhum resultado para a busca \"" . $search . "\"!";
                    }
                } else if ($pesquisaTp === "relatorios"){
                    date_default_timezone_set('America/Sao_Paulo');
                    $data = date("Y-m-d", strtotime(str_replace("/", "-", $search)));
                    if($login_data[3] !== 'empresas'){
                        @$id_empresa = mysqli_fetch_array(mysqli_query($mysql, "SELECT id FROM empresas WHERE nome LIKE '%$search%'"), MYSQLI_ASSOC)['id'];
                        if($id_empresa){
                            $query_select = "SELECT * FROM relatorios WHERE empresa_id = '$id_empresa'";
                        } else {
                            $query_select = "SELECT * FROM relatorios WHERE id = '$search' OR mes = '" . array_search($search+1, $months ) . "' OR ano = '$search' OR data_criacao = '$data'";
                        }
                    } else {
                        $id = $login_data[2];
                        @$id_empresa = mysqli_fetch_array(mysqli_query($mysql, "SELECT id FROM empresas WHERE nome LIKE '%$search%' AND id = '$id'"), MYSQLI_ASSOC)['id'];
                        if($id_empresa){
                            $query_select = "SELECT * FROM relatorios WHERE empresa_id = '$id_empresa'";
                        } else {
                            $query_select = "SELECT * FROM relatorios WHERE (id = '$search' OR mes = '" . array_search($search+1, $months) . "' OR ano = '$search' OR data_criacao = '$data') AND empresa_id = '$id'";
                        }
                    }
                    
                    $select_result = mysqli_query($mysql, $query_select);
                    $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);
                    
                    if(count($array_select) > 0) {
        ?>
                        <table class="mainTable" cellspacing="0">
                            <thead class="tableHead">
                                <tr>
                                    <th>ID</th>
                                    <th>Mês</th>
                                    <th>Ano</th>
                                    <th>Data de Criação</th>
                                    <th>Empresa</th>
                                    <th>Download</th>
                                    <?php 
                                        if($login_data[3] !== 'empresas') {
                                            echo '<th>Editar</th><th>Excluir</th>';
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody class="tableBody">
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
        ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td><?php echo $months[$mes-1]; ?></td>
                                    <td><?php echo $ano; ?></td>
                                    <td><?php echo $data_criacao; ?></td>
                                    <td><a style="text-decoration: underline;" href="empresas.php?id=<?php echo $empresa_id; ?>"><?php echo $nome_empresa; ?></a></td>
                                    <td class="download"><a href="<?php echo $local_arquivo ?>" download><i class="fa-solid fa-download"></i></a></td>
                                    <?php 
                                        if($login_data[3] !== 'empresas') {
                                            echo '<td class="editRow"><a href="atualizar.php?id=' . $id . '&type=rel"><i class="fa-solid fa-pen"></i></a></td><td class="deleteRow"><a href="deletar.php?id=' . $id . '&type=rel"><i class="fa-solid fa-trash"></i></a></td>';
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
                        echo "<p>Não foi encontrado nenhum resultado para a busca \"" . $search . "\"!";
                    }
                } else if($pesquisaTp === 'alunos') {
                    if($login_data[3] === 'escolas'){
                        $query_select = "SELECT * FROM alunos WHERE id = '$search' OR nome LIKE '%$search%' OR cpf = '$search' OR email = '$search' OR telefone = '$search' OR cep = '$search' OR logradouro = '$search' OR bairro = '$search' OR cidade = '$search' OR estado = '$search'";
                    } else if ($login_data[3] === 'empresas'){
                        $id_empresa = $login_data[2];
                        $query_select = "SELECT * FROM alunos WHERE id = '$search' OR nome LIKE '%$search%' OR cpf = '$search' OR email = '$search' OR telefone = '$search' OR cep = '$search' OR logradouro = '$search' OR bairro = '$search' OR cidade = '$search' OR estado = '$search' AND empresa_id = '$id_empresa'";
                    }

                    $select_result = mysqli_query($mysql, $query_select);
                    $array_select = mysqli_fetch_all($select_result, MYSQLI_ASSOC);

                    if(count($array_select) > 0){
                    ?>
                        <table class="mainTable" cellspacing="0">
                            <thead class="tableHead">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Telefone</th>
                                    <th>E-mail</th>
                                    <th>Escola</th>
                                    <?php
                                        if($login_data[3] === 'escolas') {
                                            echo '
                                            <th>Empresa</th>
                                            <th>Localização</th>
                                            <th>Solicitações</th>
                                            <th>Editar</th>
                                            <th>Excluir</th>';
                                        } else if($login_data[3] === 'empresas'){
                                            echo '<th>Solicitações</th>';
                                        }
                                        if($login_data[3] !== 'alunos'){
                                            echo '<th>Status</th>';
                                        } 
                                    ?>
                                </tr>
                            </thead>
                            <tbody class="tableBody">
                                <?php
                                    for($i = 0; $i < count($array_select); $i++){
                                        $row = $array_select[$i];
                                        $id = $row['id'];
                                        $nome = ucwords(strtolower($row['nome']));
                                        $cpf = $row['cpf'];
                                        $telefone = $row['telefone'];
                                        $email = strtolower($row['email']);
                                        $status = $row['usuario_ativo'] == 1 ? "Ativo" : "Desativado";

                                        $escola_id = $row['escola_id'];
                                        $dados_escola = mysqli_fetch_array(mysqli_query($mysql, "SELECT * FROM escolas WHERE id = '$escola_id'"));
                                        $nome_escola = $dados_escola['nome'];

                                        $empresa_id = $row['empresa_id'];
                                        $dados_empresa = mysqli_fetch_array(mysqli_query($mysql, "SELECT * FROM empresas WHERE id = '$empresa_id'"));
                                        $nome_empresa = $dados_empresa['nome'];

                                        $cep = $row['cep'];
                                        $logradouro = strtoupper($row['logradouro']);
                                        $num = $row['num'];
                                        $bairro = strtoupper($row['bairro']);
                                        $complemento = strtoupper($row['complemento']);
                                        $cidade = strtoupper($row['cidade']);
                                        $estado = strtoupper($row['estado']);
                                        $endereco = "$logradouro, $num - $bairro, $cidade - $estado, $cep";
                                ?>
                                    <tr>
                                        <td><?php echo $id; ?></td>
                                        <td><?php echo $nome; ?></td>
                                        <?php
                                            echo "<script>formatElement('$cpf', 'cpf')</script>";
                                            $type = strlen($telefone) <= 10 ? 'telefone_fixo' : 'telefone';
                                            echo "<script>formatElement('$telefone', '$type')</script>" 
                                        ?>
                                        <td><?php echo $email; ?></td>
                                        <td><?php echo $nome_escola; ?></td>
                                        <?php 
                                            if($login_data[3] === 'escolas'){
                                                echo '
                                                <td><a style="text-decoration: underline;" href="empresas.php?id=' . $empresa_id .'">' . $nome_empresa . '</a></td>
                                                <td><a href="https://www.google.com/maps/search/' . $endereco .'" target="_blank"><i class="fa-solid fa-map-location-dot"></i></a></td>
                                                <td><a href="solicitacoes.php?alu_id=' . $id .'"><i class="fa-regular fa-paper-plane"></i></a></td>
                                                <td class="editRow"><a href="atualizar.php?id=' . $id . '&type=alu"><i class="fa-solid fa-pen"></i></a></td>
                                                <td class="deleteRow"><a href="deletar.php?id=' . $id . '&type=alu"><i class="fa-solid fa-trash"></i></a></td>';
                                            } else if($login_data[3] === 'empresas'){
                                                echo '<td><a href="solicitacoes.php?alu_id=' . $id .'"><i class="fa-regular fa-paper-plane"></i></a></td>';
                                            }
                                            if($login_data[3] !== 'alunos'){
                                                echo "<td>$status</td>";
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
                        echo "<p>Não foi encontrado nenhum resultado para a busca \"" . $search . "\"!";
                    }
                } else if($pesquisaTp === 'solicitacoes'){

                }
                    mysqli_close($mysql);
                }
        ?>
    </div>
</body>
</html>