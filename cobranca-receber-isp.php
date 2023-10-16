<?php
session_start();
include('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina


mysqli_query($conexao, "UPDATE user_cobranca SET datapagamento=NOW(),situacao='RECEBIDO' WHERE id='$_GET[id]'") or die(mysqli_error($conexao));
echo persona('Recebido');

