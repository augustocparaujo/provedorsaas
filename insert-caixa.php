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

if(!empty(@$idempresa)){

    $tipo = $_POST['tipo'];
    $descricao = AspasBanco($_POST['descricao']);

    $cartaocredito = Moeda($_POST['cartaocredito']);
    $cartaodebito = Moeda($_POST['cartaodebito']);
    $pix = Moeda($_POST['pix']);
    $dinheiro = Moeda($_POST['dinheiro']);
    $valorpago = $cartaocredito + $cartaodebito + $pix + $dinheiro;

    $data = dataBanco($_POST['data']);
    $datap = dataBanco($_POST['data']);

        if(!empty($tipo) || !empty($descricao) || !empty($data)){

            mysqli_query($conexao,"INSERT INTO caixa (idempresa,tipo,nomecliente,descricao,valor,valorpago,dinheiro,cartaocredito,cartaodebito,pix,data,datapagamento,user) 
            VALUES ('$idempresa','$tipo','$nomeuser','$descricao','$valorpago','$valorpago','$dinheiro','$cartaocredito','$cartaodebito','$pix','$data','$datap','$nomeuser')") or die (mysqli_error($conexao));

            echo insert();

        }else{
            echo persona('Preencher campos obrigatórios');
        }

}else{
    echo persona('Algo deu errado :( !');
}
?>            