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
require_once('routeros_api.class.php');
$mk = new RouterosAPI();  
if($mk->connect($_SESSION['ip'], decrypt($_SESSION['user']), decrypt($_SESSION['password']))) {
    //remover
    $find = $mk->comm("/ppp/active/remove", array(
        ".id" =>  $id,
        ));

        echo persona('Cliente derrubado');
}else{
    echo persona('Conectar no servidor');
}
?>