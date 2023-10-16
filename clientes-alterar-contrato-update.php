<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$logomarcauser = $_SESSION['logomarcauser'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true){echo '<script>location.href="sair.php";</script>'; }

//idcliente
//idcontrato
$idcliente = $_POST['idcliente'];
$idcontrato = $_POST['idcontrato'];

mysqli_query($conexao,"UPDATE contratos SET idcliente='$idcliente' WHERE id='$idcontrato'") or die (mysqli_error($conexao)); 

echo update();   
?>