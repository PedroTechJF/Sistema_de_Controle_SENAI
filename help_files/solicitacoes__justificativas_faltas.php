<?php
    if (basename(__FILE__, '') === basename($_SERVER['SCRIPT_FILENAME'], '')) {
        echo "<script>window.location.href = '../erro.php?msg_id=1'</script>";
    }
?>
<table class="mainTable" cellspacing="0">
    <thead class="tableHead">
        <tr>
            <th>ID</th>
            <th>Data de Inicial</th>
            <th>Data Final</th>
            <th>Justificativa</th>
            <th>Observação</th>
            <th>Status</th>
            <th>Curso</th>
            <th>Empresa</th>
            <th>Arquivo</th>
        </tr>
    </thead>
    <tbody>
        <?php
            for($i = 0; $i < count($array_select); $i++){
                $row = $array_select[$i];
                $id = $row['id'];
                $data_inicial = date('d/m/Y', strtotime($row['data_inicial']));
                $data_final = date('d/m/Y', strtotime($row['data_final']));
                $justificativa = $row['justificativa'];
                $observacao = $row['observacao'] !== "" ? $row['observacao'] : "Sem observação";
                $status = $row['status'];
                $local_arquivo = $row['local_arquivo'];
                $nome_arquivo = substr($local_arquivo, strrpos($local_arquivo, '/') + 1);
                $curso_id = $row['curso_id'];
                $empresa_id = $row['empresa_id'];
                $dados_curso = mysqli_fetch_array(mysqli_query($mysql, "SELECT * FROM cursos WHERE id = '$curso_id'"));
                $nome_curso = $dados_curso['nome'];
                $dados_empresa = mysqli_fetch_array(mysqli_query($mysql, "SELECT * FROM empresas WHERE id = '$empresa_id'"));
                $nome_empresa = $dados_empresa['nome'];
        ?>
            <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $data_inicial ?></td>
                <td><?php echo $data_final; ?></td>
                <td><?php echo $justificativa; ?></td>
                <td><?php echo $observacao !== null ? $observacao : "Sem Observação"; ?></td>
                <td><?php echo $status; ?></td>
                <td><?php echo $nome_curso; ?></td>
                <td><?php echo $nome_empresa; ?></td>
                <td class="download"><a href="<?php echo $local_arquivo; ?>" download><i class="fa-solid fa-download"></i></a></td> 
            </tr>
        <?php 
            }
        ?>
    </tbody>
</table>
<div class="extraBtns" style="flex-direction: row;">
    <input type="button" onclick="window.location.href = 'solicitacoes.php'" value="Voltar">
    <?php
        if($login_data[3] === 'escolas') {
            echo "<input type='button' onclick=\"window.location.href='atualizar.php?id=$id&type=sol_just'\" value='Editar Solicitação'>";
        }
    ?>
</div>