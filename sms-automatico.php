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

$inicio = date('Y-m-01');
$fim = date('Y-m-t');
$tipo = $_POST['tipo']; //pega contato-> tabela cliente, valor,vencimento,link ->tabela cabranca de acordo com tipo= pendente ou vencido
$msg = str_replace(' ','+',$_POST['msg']);
//1-tabela cliente->contato
$query = mysqli_query($conexao,"SELECT cobranca.*, cliente.contato, user.nome as empresa FROM cobranca
        LEFT JOIN cliente ON cobranca.idcliente = cliente.id
        LEFT JOIN user ON cobranca.idempresa = user.idempresa
        WHERE cobranca.idempresa='$idempresa' AND cobranca.situacao='$tipo' AND cobranca.vencimento BETWEEN '$inicio' AND '$fim' AND cobranca.link <> '' AND cliente.contato <> ''");
		if(mysqli_num_rows($query) > 0){ 
        while($ret = mysqli_fetch_array($query)){        
          $stringvalor = str_replace('{valor}', Real($ret['valor']), $msg);
          $stringvencimento = str_replace('{vencimento}', dataForm($ret['vencimento']), $stringvalor);
          $stringlink = str_replace('{link}',$ret['link'], $stringvencimento);
          $stringcompleta = str_replace('{empresa}',$ret['empresa'], $stringlink);
          $contato = $ret['contato'];
          include_once 'api_smsnet.php';
          $mensagem = $stringcompleta;          
          enviaSms($contato,$mensagem); 
        }        
    }else{ echo persona('Sem cobranças em aberto'); }
?>