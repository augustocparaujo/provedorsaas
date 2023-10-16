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
        $id = $_GET['id'];
        $ncobranca = $_GET['ncob'];

        if(!empty($id)){
            //tabela cobranca
            mysqli_query($conexao,"UPDATE cobranca SET valorpago='0.00',datapagamento='0000-00-00',situacao='PENDENTE' WHERE id='$id'") or die (mysqli_error($conexao));

            //rabela caixa
            if(!empty($ncobranca)){
                mysqli_query($conexao,"DELETE FROM caixa WHERE nrecibo='$ncobranca'") or die (mysqli_error($conexao));
            }

            echo persona('Estornado com sucesso.');
        }
}else{
    echo persona('Algo deu errao :( !');
}
?>