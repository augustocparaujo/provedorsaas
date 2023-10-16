<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$query = mysqli_query($conexao,"SELECT log_cobranca.*, user.nome AS empresa FROM log_cobranca
LEFT JOIN user ON log_cobranca.idempresa = user.idempresa ORDER BY id DESC") or die (mysqli_error($conexao)); 
echo '
<div class="card-body table-responsive p-0">
<table id="example" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Data</th>
            <th>Empresa</th>
            <th>Cliente</th>
            <th>Erro</th>
        </tr>
    </thead>
    <tbody>';
    while($dd = mysqli_fetch_array($query)){
        echo '
        <tr>
            <td>'.$dd['id'].'</td>
            <td>'.dataForm($dd['data']).'</td>
            <td>'.$dd['empresa'].'</td>
            <td>'.$dd['cliente'].'</td>
            <td>'.$dd['log'].'</td>
        </tr>';
}
        echo'
    </tbody>
</table></div>';
?>
<script type="text/javascript" src="plugins/dataTable/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="plugins/dataTable/jquery.dataTables.min.css" />
<script>
$(document).ready(function() {
    $('#example').DataTable({
        "language": {
            "lengthMenu": "Exibir _MENU_ linhas",
            "zeroRecords": "Sem registro",
            "info": "Linhas de _PAGE_ at&eacute; _PAGES_",
            "infoEmpty": "Nenhum registro dispon&iacute;vel",
            "infoFiltered": "(filtrados de _MAX_ total de linhas)"
        },
        stateSave: true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
});
</script>