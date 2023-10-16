<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
include('conectar-servidor.php');
@$idempresa = $_SESSION['idempresa'];
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }

@$tipouser = $_SESSION['tipouser'];
$query = mysqli_query($conexao,"SELECT * FROM servidor WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
echo '
<div class="card-body table-responsive p-0">
<table id="example" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>IP</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>';
            while($dd = mysqli_fetch_array($query)){echo'
            <tr>
                <td>'.$dd['id'].'</td>
                <td>'.$dd['nome'].'</td>
                <td>'.$dd['ip'].'</td>
                <td>';
                    if($tipouser == 'Admin' || PermissaoCheck($idempresa,'servidor-editar',$iduser) == 'checked'){ echo'
                        <a href="#" onclick="alterarServidor('.$dd['id'].')" class="btn btn-social-icon btn-edit"><i class="fa fa-edit"></i></a>&ensp;';
                    }
                    if($tipouser == 'Admin' || PermissaoCheck($idempresa,'servidor-excluir',$iduser) == 'checked'){ echo'
                        <a href="#" onclick="excluirServidor('.$dd['id'].')" class="btn btn-social-icon btn-trash"><i class="fa fa-trash"></i></a>&ensp;';
                    }echo'
                    <button href="#" onclick="statusServer('.$dd['id'].')" class="btn btn-info"><i class="fa fa-spin fa-refresh"></i> Status conexão</button>

                    <a onclick="sincronzarPlanos('.$dd['id'].')" class="btn btn-primary btn-lrg ajax" title="Sincronizar planos">
                        <i class="fa fa-spin fa-refresh"></i> Sincr.Planos
                    </a>';

                    if(PermissaoCheck($idempresa,'planos',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                        <a href="planos.php?id='.$dd['id'].'&nome='.$dd['nome'].'" class="btn btn-warning" title="Planos" target="_blank"><i class="fa fa-map-signs"></i> Planos</a>';
                    }echo'

                    <a onclick="sincronzarClientes('.$dd['id'].')" class="btn btn-primary btn-lrg ajax" title="Sincronizar clientes">
                        <i class="fa fa-spin fa-refresh"> </i> Sincr.Clientes
                    </a>';
                     if(PermissaoCheck($idempresa,'clientes-off',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'                    
                    <a href="clientes-on.php?id='.$dd['id'].'" target="_blank"class="btn btn-success"><i class="fa fa-users"></i> Clientes On-line</a>';
                    }
                    if(PermissaoCheck($idempresa,'clientes-off',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'                    
                    <a href="clientes-off.php?id='.$dd['id'].'" target="_blank"class="btn bg-navy"><i class="fa fa-user-times"></i> Clientes Off-line</a>';
                    }

                    echo'
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
        "order": [
            [0, "asc"]
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
});
</script>