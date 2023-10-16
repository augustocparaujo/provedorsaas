<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
//nchamado idcliente	idempresa	nome	tipo	usuariocad	datacad	obs	usuarioatendeu	dataatendimento	obsusuario	situacao	
    $query = mysqli_query($conexao,"SELECT chamado.*, cliente.nome FROM chamado 
    LEFT JOIN cliente ON chamado.idcliente = cliente.id
    WHERE chamado.idempresa='$idempresa' GROUP BY nchamado") or die (mysqli_error($conexao));

echo '
<div class="card-body table-responsive p-0">
<table id="example" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Tipo</th>
            <th>Nome</th>            
            <th>Abertura</th>
            <th>Atendimento</th>
            <th>Atendente</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>';
            while($dd = mysqli_fetch_array($query)){echo'
            <tr style="cursor: pointer" onclick="exibir('.$dd['id'].')">
                <td>'.$dd['nchamado'].'</td>
                <td>'.$dd['tipo'].'</td>
                <td>'.$dd['nome'].'</td>
                <td>'.dataForm($dd['datacad']).'</td>
                <td>'.dataForm($dd['dataatendimento']).'</td>
                <td>'.$dd['usuariocad'].'</td>
                <td>';
                if($dd['situacao'] == 'ABERTO'){ echo'<span class="label label-info">'.$dd['situacao'].'</span>'; }
                if($dd['situacao'] == 'PENDENTE' OR $dd['situacao'] == 'PENDENTE TERCEIRO'){ echo'<span class="label label-warning">'.$dd['situacao'].'</span>'; }
                if($dd['situacao'] == 'SOLUCIONADO'){ echo'<span class="label label-success">'.$dd['situacao'].'</span>'; }
                if($dd['situacao'] == 'REABERTO'){ echo'<span class="label label-default">'.$dd['situacao'].'</span>'; }
                if($dd['situacao'] == 'CANCELADO'){ echo'<span class="label label-danger">'.$dd['situacao'].'</span>'; }
                echo'</td>
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