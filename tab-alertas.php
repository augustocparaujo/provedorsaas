<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
//tipo	idcliente	descricao	usuariocad	datacad		
    $query = mysqli_query($conexao,"SELECT alertas.*, cliente.nome AS nome FROM alertas 
    LEFT JOIN cliente ON alertas.idcliente = cliente.id
    WHERE alertas.idempresa='$idempresa' ORDER BY id DESC") or die (mysqli_error($conexao));

echo '
<div class="card-body table-responsive p-0">
<table id="example" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Tipo</th>
            <th>Cliente</th>            
            <th>Descrição</th>
            <th>Usuário</th>
            <th>Data envio</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>';
            while($dd = mysqli_fetch_array($query)){echo'
            <tr style="cursor: pointer" onclick="exibir('.$dd['id'].')">
                <td>'.$dd['id'].'</td>
                <td>'.$dd['tipo'].'</td>
                <td>'.$dd['nome'].'</td>
                <td>'.AspasForm($dd['descricao']).'</td>
                <td>'.$dd['usuariocad'].'</td>
                <td>'.dataForm($dd['datacad']).'</td>
                <td>'; if($dd['situacao'] == 'enviado'){ echo'<span class="label label-success">'.$dd['situacao'].'</span>';}echo'
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