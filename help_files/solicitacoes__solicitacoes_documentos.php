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
            <th>Documento</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $valorFinal = 0;
            for($i = 0; $i < count($array_select); $i++){
                $row = $array_select[$i];
                $id = $row['id'];
                $data_solicitacao = date('d/m/Y', strtotime($row['data_solicitacao']));
                $data_entrega_estimada = date('d/m/Y', strtotime($row['data_entrega_estimada']));
                $data_entrega = $row['data_entrega'];
                $status = $row['status'];
                $documento_id = $row['documentos_id'];
                $valor_doc = $row['valor_solicitacao'];
                $valorFinal += $valor_doc;
                $local_boleto = $row['local_boleto'];
                $dados_documento = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM documentos WHERE id = '$documento_id'"));
        ?>
            <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $data_solicitacao ?></td>
                <td><?php echo $data_entrega_estimada; ?></td>
                <td><?php echo $data_entrega !== null ? date('d/m/Y', strtotime($row['data_entrega'])) : "Não entregue"; ?></td>
                <td><?php echo $status; ?></td>
                <td><?php echo $dados_documento['nome']; ?></td>
                <td><?php echo "R$ " . $dados_documento['valor'] . ",00"; ?></td>
            </tr>
        <?php 
            }
        ?>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Valor Final</th>
            <td><?php echo "R$ $valorFinal,00"; ?></td>
        </tr>
    </tbody>
</table>
<div class="extraBtns" style="flex-direction: row;">
    <input type="button" onclick="window.location.href = 'solicitacoes.php'" value="Voltar">
    <?php
        if($login_data[3] === 'escolas') {
            echo "<input type='button' onclick=\"window.location.href='atualizar.php?id=$id&type=sol_doc'\" value='Editar Solicitação'>";
        }
        if($local_boleto !== null && !in_array($status, ["Pagamento Aprovado", "Disponível para Retirada"])){
            // $arquivo = substr($local_boleto, 1, null);
            // echo "<input type='button' onclick=\"window.open(`/\${window.location.pathname.split('/').splice(1, 2).join('/')}$arquivo`, '_blank')\" value='Baixar Boleto'>";
            echo "<input type='button' onclick=\"window.open('$local_boleto', '_blank')\" value='Baixar Boleto'>";
        }
    ?>
</div>