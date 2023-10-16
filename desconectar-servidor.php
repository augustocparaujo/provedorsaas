<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

$id = $_GET['id'];
if(!empty($id)){
    $query = mysqli_query($conexao,"SELECT * FROM servidor WHERE id='$id'") or die (mysqli_error($conexao));
    $dd = mysqli_fetch_array($query);

    //limpar
    unset($_SESSION['idservidor']);
    unset($_SESSION['servidor']);
    unset($_SESSION['ip']);
    unset($_SESSION['user']);
    unset($_SESSION['password']);
    unset($_SESSION['totalClientes']);

    echo persona('Desconectado do servidor');
            
}else{
    echo persona('Falta informação!');
}

?>