<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$tipouser = @$_SESSION['tipouser'];
@$iduser = $_SESSION['iduser'];
$query = mysqli_query($conexao,"SELECT * FROM usuarios WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));

echo '
<div class="card-body table-responsive p-0">
<table id="example" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Login</th>
            <th>Cadastro</th>
            <th>Data</th>
            <th>Situação</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>';
            while($dd = mysqli_fetch_array($query)){
                echo'
                <tr>
                <td>'.$dd['id'].'</td>
                <td>'.$dd['nome'].'</td>
                <td>'.$dd['logintxt'].'</td>
                <td>'.$dd['usuariocad'].'</td>
                <td>'.dataForm($dd['datacad']).'</td>
                <td>';
                if($dd['situacao'] == 1){ 
                    echo'<small class="label label-success">ativo</small>';
                }else{ 
                    echo'<small class="label label-danger">inativo</small>';
                } 
                echo'</td>
                
                <td>';
                if($_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'usuarios-editar',$iduser) == 'checked'){ echo'
                    <a href="perfil-usuario-staff.php?id='.$dd['id'].'" class="btn btn-social-icon btn-edit" title="editar"><i class="fa fa-edit"></i></a>&ensp;';
                }
                if($_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'usuarios-ativar',$iduser) == 'checked'){
                    if($dd['situacao'] == 1){
                        echo'<a href="#" onclick="ativar('.$dd['id'].',0)" title="desativar" class="btn btn-social-icon btn-dollar"><i class="fa fa-toggle-on"></i></a>&ensp; ';
                    }else{
                        echo'<a href="#" onclick="ativar('.$dd['id'].',1)" title="ativar" class="btn btn-social-icon btn-gray"><i class="fa fa-toggle-off"></i></a>&ensp; ';
                    }
                }
                if($_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'usuarios-excluir',$iduser) == 'checked'){
                    echo'<a href="#" onclick="excluirUsuario('.$dd['id'].')" title="excuir" class="btn btn-social-icon btn-trash"><i class="fa fa-trash"></i></a>';
                }echo'
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