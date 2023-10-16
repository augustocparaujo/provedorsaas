<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a�o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

$tipo = $_POST['tipo'];
$idcliente = $_POST['idcliente'];
$idcontrato = $_POST['idcontrato'];
$nchamado = date('dmYHms').'-'.$idcliente;
$obs = AspasBanco($_POST['obs']);
$dataatendimento = date($_POST['dataatendimento']);
$situacao = $_POST['situacao'];

$queryc = mysqli_query($conexao,"SELECT cliente.nome FROM cliente WHERE id='$idcliente'") or die (mysqli_error($conexao));
$ddc = mysqli_fetch_array($queryc);
$nomecliente = $ddc['nome'];

if(!empty($idcliente) AND !empty($tipo)){
    mysqli_query($conexao,"INSERT INTO chamado 
    (nchamado,idcliente,idcontrato,idempresa,nomecliente,tipo,usuariocad,datacad,obs,dataatendimento,situacao)
    VALUES ('$nchamado','$idcliente','$idcontrato','$idempresa','$nomecliente','$tipo','$nomeuser',NOW(),'$obs','$dataatendimento',
    '$situacao')") or die (mysqli_error($conexao));

    mysqli_query($conexao,"INSERT INTO log_chamado (nchamado,usuariocad,datacad,obs,situacao) 
    VALUES('$nchamado','$nomeuser',NOW(),'$obs','$situacao')") or die (mysqli_error($conexao));

    echo insert();
    
}else{
    echo persona('Preencher campos obrigatórios.');
}

?>