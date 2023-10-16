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

$id = $_GET['id'];
$msg = str_replace(' ','+',"Título a vencer ou vencido, se já pagou desconsidera a mensagem: Valor: {valor}, Mês: {vencimento}, link: {link}");

$query = mysqli_query($conexao,"SELECT cobranca.*, cliente.contato FROM cobranca INNER JOIN cliente ON cobranca.idcliente = cliente.id WHERE cobranca.id='$id' AND cliente.contato <> '' LIMIT 1");
		if(mysqli_num_rows($query) > 0){ 

        $ret = mysqli_fetch_array($query);
        $stringvalor = str_replace('{valor}', Real($ret['valor']), $msg);
        $stringvencimento = str_replace('{vencimento}', dataForm($ret['vencimento']), $stringvalor);
        $stringcompleta = str_replace('{link}',$ret['link'], $stringvencimento);
        $contato = $ret['contato'];
        include_once 'api_smsnet.php';
        $mensagem = $stringcompleta;          
        enviaSms($contato,$mensagem);
        echo persona('Enviado com sucesso');
           
    }else{ echo persona('Sem cobranças em aberto'); }
?>