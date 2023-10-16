<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true){echo '<script>location.href="sair.php";</script>'; }

$id = $_GET['id'];
if(!empty($id)){

        mysqli_query($conexao,"DELETE FROM notificacao_agendada WHERE id='$id'") or die (mysqli_error($conexao));

        echo delete();
}else{
    echo persona('Falta informação!');
}

?>