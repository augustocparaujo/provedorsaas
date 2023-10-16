<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }

@$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM plano WHERE servidor='$id' AND idempresa='$idempresa'") or die (mysqli_error($conexao));
if(mysqli_num_rows($query) >= 1){
    while($dd = mysqli_fetch_array($query)){
        echo'
        <tr>
            <td>'.$dd['plano'].'</td>
            <td>'.$dd['enderecolocal'].'</td>
            <td>'.$dd['enderecoremoto'].'</td>
            <td>'.$dd['velocidade'].'</td>
            <td>'.Real($dd['valor']).'</td>
            <td>';
            if($_SESSION['tipouser'] == 'Admin' OR PermissaoCheck($idempresa,'planos-editar',$iduser) == 'checked'){echo' 
                <a href="#" onclick="alterar('.$dd['id'].')" class="btn btn-social-icon btn-edit" title="alterar"><i class="fa fa-pencil"></i></a>&emsp;';
              }
              if($_SESSION['tipouser'] == 'Admin' OR PermissaoCheck($idempresa,'planos-excluir',$iduser) == 'checked'){ echo'
                <a href="#" onclick="excluir('.$dd['id'].')" class="btn btn-social-icon btn-trash" title="excluir"><i class="fa fa-trash"></i></a>';
              }echo'
            
            </td>
        </tr>';
    }
}else{ echo'<tr><td colspan="8">Sem registro</td></tr>'; }

?>