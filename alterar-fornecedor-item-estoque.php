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
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }

$id = $_POST['id'];
$descricao = AspasBanco($_POST['descricao']);
if(!empty($_POST['id']) || !empty($_POST['descricao'])){
    mysqli_query($conexao,"UPDATE j_fornecedor_equip SET descricao='$descricao' WHERE id='$id'")
    or die (mysqli_error($conexao));

    echo update();

}else{
    echo persona('Erro inesperado');
}

?>