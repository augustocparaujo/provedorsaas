<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
include_once('routeros_api.class.php');
@$idempresa = $_SESSION['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$iduser = $_SESSION['iduser'];
@$iduser = $_SESSION['iduser'];
@$tipouser = $_SESSION['tipouser'];

if(isset($_SESSION['iduser'])!=true AND isset($_SESSION['situacaouser'])!=true){echo '<script>location.href="sair.php";</script>'; }

@$id = $_GET['id']; 
$query = mysqli_query($conexao,"SELECT * FROM historico WHERE idcliente='$id'") or die (mysqli_error($conexao));
            while($dd = mysqli_fetch_array($query)){echo'
            <tr>
                <td>'.$dd['id'].'</td>
                <td>'.AspasForm($dd['obs']).'</td>
                <td>'.$dd['usuariocad'].'</td>
                <td>'.dataForm($dd['datacad']).'</td>
                <td>'.$dd['usuarioatualizou'].'</td>
                <td>'; if($dd['dataatualizacao'] != '0000-00-00'){ echo dataForm($dd['dataatualizacao']);} echo'</td>
                <td>';
                    if($_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'historico-alterar',$iduser) == 'checked'){ echo'
                    <a href="#" onclick="alterarHistorico('.$dd['id'].')"><i class="fa fa-edit fa-2x"></i></a>&ensp;';}
                    
                    if($_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'historico-excluir',$iduser) == 'checked'){ echo'
                    <a href="#" onclick="excluirHistorico('.$dd['id'].')"><i class="fa fa-trash fa-2x text-red"></i></a>&ensp;';
                        
                    }
                    echo'
                </td>
            </tr>';
            }
?>