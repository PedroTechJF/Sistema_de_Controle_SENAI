<?php
    if (basename(__FILE__, '') === basename($_SERVER['SCRIPT_FILENAME'], '')) {
        echo "<script>window.location.href = '../erro.php?msg_id=1'</script>";
    }
?>
<table class="mainTable" cellspacing="0">
    <thead class="tableHead">
        <tr>
            <th>ID</th>
            <th>Data de Solicitação</th>
            <th>Data de Entrega Estimada</th>
            <th>Data de Entrega</th>
            <th>Status</th>
            <th>Tipo de Solicitação</th>
            <?php 
                if($login_data[3] !== 'alunos') {
                    echo '
                    <th>Aluno</th>
                    <th>Expandir</th>';
                } else {
                    echo '<th>Expandir</th>';
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
            for($i = 0; $i < count($array_select); $i++){
                $row = $array_select[$i];
                $id = $row['id'];
                $aluno_id = $row['aluno_id'];
                $data_solicitacao = date('d/m/Y', strtotime($row['data_solicitacao']));
                $data_entrega_estimada = date('d/m/Y', strtotime($row['data_entrega_estimada']));
                $data_entrega = $row['data_entrega'];
                $status = $row['status'];
                $tipo_solicitacao = $row['tipo_solicitacao'];
                $db_child = $row['db_child'];
                // $solicitacao_id = $row['solicitacao_id'];
        ?>
            <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $data_solicitacao ?></td>
                <td><?php echo $data_entrega_estimada; ?></td>
                <td><?php echo $data_entrega !== null ? date('d/m/Y', strtotime($data_entrega)) : "Não entregue"; ?></td>
                <td><?php echo $status; ?></td>
                <td><?php echo $tipo_solicitacao; ?></td>
                <?php 
                    if($login_data[3] !== 'alunos') {
                        echo '
                        <td><a href="alunos.php?id=' . $aluno_id . '"><i class="fa-regular fa-user"></i></a></td>
                        <td><a href="solicitacoes.php?sol_id='. $id. '&sol_type=' . $db_child .'"><i class="fa-regular fa-folder-open"></i></td>';
                    } else {
                        echo '<td><a href="solicitacoes.php?sol_id='. $id. '&sol_type=' . $db_child .'"><i class="fa-regular fa-folder-open"></i></td>';
                    }
                ?>
            </tr>
        <?php 
            }
        ?>
    </tbody>
</table>