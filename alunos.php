<?php
    require "help_files/db.php";
    @$login = $_COOKIE['login'];
    @$login_data = explode(",", $login);
    @$id_user = $login_data[2];
    if (!isset($_COOKIE['login'])) {
        echo "<script>window.location.href = 'login.php'</script>";
    } else if($login_data[3] === 'alunos'){
        echo "<script>window.location.href = 'erro.php?msg_id=1'</script>";
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Alunos</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="imgs/favicon.png" type="image/x-icon">
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
        document.querySelector('#pesquisaTp').value = 'alunos'
    </script>
    <script src="js/main.js"></script>
    <div class="container">
        <?php
            $query_select = "SELECT * FROM alunos";
            if($login_data[3] === 'empresas'){
                $query_select = "SELECT * FROM alunos WHERE empresa_id = '$id_user'";
            }
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                $query_select = "SELECT * FROM alunos WHERE id = '$id'";
                if($login_data[3] === 'empresas'){
                    $query_select = "SELECT * FROM alunos WHERE id = '$id' AND empresa_id = '$id_user'";
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
                echo "<p>Não Há Nenhum Relátorio Enviado!</p>";
            }
            mysqli_close($mysql);
            ?>
    </div>
</body>
</html>