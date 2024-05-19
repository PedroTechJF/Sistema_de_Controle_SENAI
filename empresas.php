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
        document.querySelector('#pesquisaTp').value = 'empresas'
    </script>
    <div class="container">
    <script src="js/main.js"></script>
        <?php 
            require "help_files/db.php";
            $query_select = "SELECT * FROM empresas";
            $i = 1;
            @$id = $_GET['id'];
            if(isset($id)){
                $query_select = "SELECT * FROM empresas WHERE id = '$id'";
                $i = 0;
            }
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
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="tableBody">
                        <?php
                            for($i; $i < count($array_select); $i++){
                                $row = $array_select[$i];
                                $id = $row['id'];
                                $nome = $row['nome'];
                                $cnpj = $row['cnpj'];
                                $email = strtolower($row['email']);
                                $telefone = $row['telefone'];
                                $status = $row['usuario_ativo'] == 1 ? "Ativa" : "Desativada";

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
                                <td><?php echo $status; ?></td>
                            </tr>

                        <?php 
                            }
                        ?>
                    </tbody>
                </table>
        <?php 
            } else {
                echo "<p>Não Existe Nenhuma Empresa Cadastrada!</p>";
            }
            mysqli_close($mysql);
        ?>
    </div>
</body>
</html>