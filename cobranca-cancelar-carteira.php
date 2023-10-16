<?php
ob_start();
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$iduser = $_SESSION['iduser'];
if(isset($_SESSION['iduser'])!=true){echo '<script>location.href="sair.php";</script>'; }


//cancelar
$id = $_GET['id'];


    mysqli_query($conexao,"UPDATE cobranca SET situacao='CANCELADO',usuarioatualizou='$nomeuser',atualizado=NOW() WHERE id='$id'") or die (mysqli_error($conexao));
    
    echo persona('Cancelado com sucesso');


?>