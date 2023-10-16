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
    
            mysqli_query($conexao,"DELETE FROM historico WHERE id='$id'") or die (mysqli_error($conexao));

            echo delete();

}else{
    echo persona('Algo deu errado :( !');
}
?>            