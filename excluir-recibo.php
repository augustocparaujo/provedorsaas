<?php 
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

@$id = $_GET['id'];
//nrecibo
$query = mysqli_query($conexao,"SELECT * FROM recibosavulso WHERE id='$id'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
$nrecibo = $dd['nrecibo'];

if(!empty($id) AND !empty($nrecibo) AND !empty($idempresa)){

    mysqli_query($conexao,"DELETE FROM recibosavulso WHERE id='$id'") or die (mysqli_error($conexao));
    mysqli_query($conexao,"DELETE FROM caixa WHERE nrecibo='$nrecibo'") or die (mysqli_error($conexao));    

echo delete();

}else{
    echo persona('Algo deu errado :( !');
}
?>            