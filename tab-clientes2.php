<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$query = mysqli_query($conexao,"SELECT * FROM user") or die (mysqli_error($conexao));
echo '
<div class="card-body table-responsive p-0">
<table id="example" style="width:100%">
    <thead>
        <tr>
            <th>Nome</th>
            <th>QTN Clientes</th>
            <th>QTN Cobranças</th>
            <th>QTN Users</th>
            <th>Cadastro</th>
            <th>Período</th>
            <th>Situação</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>';
            while($dd = mysqli_fetch_array($query)){  
                $periodo = date('d-m-Y',strtotime('+7 days',strtotime($dd['datacadastro'])));
                if(date('d-m-Y') > $periodo){ $sit='<small class="label label-danger">Teste finalizado</small>'; } else { $sit = $periodo; }
                
                $query0 = mysqli_query($conexao,"SELECT cliente.idempresa FROM cliente WHERE idempresa=".$dd['idempresa']."") or die (mysqli_error($conexao));
                $rows = mysqli_num_rows($query0);
                $query1 = mysqli_query($conexao,"SELECT cobranca.idempresa FROM cobranca WHERE idempresa=".$dd['idempresa']." AND situacao IN ('PENDENTE','VENCIDA')") or die (mysqli_error($conexao));
                $rowsc = mysqli_num_rows($query1);
                $query2 = mysqli_query($conexao,"SELECT usuarios.idempresa FROM usuarios WHERE idempresa=".$dd['idempresa']."") or die (mysqli_error($conexao));
                $rowsu = mysqli_num_rows($query2);
                echo'
            <tr>
                <td>'; if($dd['fantasia'] != ''){ echo $dd['fantasia']; }else{ echo $dd['nome']; } echo'</td>
                <td>'.$rows.'</td>
                <td>'.$rowsc.'</td>
                <td>'.$rowsu.'</td>
                <td>'.dataForm($dd['datacadastro']).'</td>
                <td>'.$sit.'</td>
                <td>';
                if($dd['situacao'] == 1){ 
                    echo'<small class="label label-success">ativo</small>';
                }else{ 
                    echo'<small class="label label-danger">inativo</small>';
                } 
                echo'</td>
                <td>';
                if($dd['situacao'] == 1){
                    echo'
                        <a href="#" onclick="ativar('.$dd['id'].',0)" title="desativar" class="fa fa-toggle-on text-green fa-2x"></a>&ensp; 
                        <a href="#" onclick="resetSenha('.$dd['id'].')" class="fa fa-key text-red fa-2x" title="Resetar senha"></a>';
                }else{
                    echo'<a href="#" onclick="ativar('.$dd['id'].',1)" title="ativar" class="fa fa-toggle-off text-gray fa-2x"></a>';
                }
                echo'
                &ensp;<a href="perfil-usuario-isp.php?id='.$dd['id'].'" title="Perfil ISP" class="fa fa-gears text-black fa-2x"></a>
                &ensp;<a href="#" onclick="excluir2('.$dd['idempresa'].')" title="Excluir ISP" class="fa fa-trash text-red fa-2x"></a>
                </td>
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