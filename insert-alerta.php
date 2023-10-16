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

//verificar se é pra todos vem id do clietne ou pra geral
//tipo	idempresa	idcliente	descricao	usuariocad	datacad	situacao	

$tipo = $_POST['tipo'];
$idcliente = $_POST['cliente'];
$descricao = AspasBanco($_POST['descricao']);

if(!empty($tipo) AND !empty($descricao) AND !empty($idempresa)){
    mysqli_query($conexao,"INSERT INTO alertas (tipo,idempresa,idcliente,descricao,usuariocad,datacad,situacao)
    VALUES ('$tipo','$idempresa','$idcliente','$descricao','$nomeuser',NOW(),'enviado')") or die (mysqli_error($conexao));

    echo insert();
    
}else{
    echo persona('Preencher campos obrigatórios.');
}

?>