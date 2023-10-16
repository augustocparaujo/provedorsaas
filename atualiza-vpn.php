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
@$tipo = $_GET['tipo'];
@$login = AspasBanco($_POST['login']);
@$senha = AspasBanco($_POST['senha']);
@$ip = AspasBanco($_POST['ip']);


if($tipo == '' || !empty($login) || !empty($senha) || !empty($ip)){
    
    mysqli_query($conexao,"INSERT INTO vpn_gisp (login,senha,ip) VALUES ('$login','$senha','$ip')") or die (mysqli_error($conexao));

    echo insert();

}elseif($tipo == 1 || !empty($id)){

    mysqli_query($conexao,"DELETE FROM vpn_gisp WHERE id='$id'") or die (mysqli_error($conexao));

    echo delete();

}

?>