<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$logomarcauser = $_SESSION['logomarcauser'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
$hoje = date('Y-m-d');

//usuario,item,valor,datacad,usuariocad
$id = $_POST['usuario'];
mysqli_query($conexao,"UPDATE permissao SET valor='',datacad='$hoje',usuariocad='$iduser' WHERE idempresa='$idempresa' AND usuario='$id'");
if(isset($_POST['permissoes'] )!= ''){
    foreach($_POST['permissoes'] as $item) { 
        Permissao($idempresa,$item,$id,$iduser);
    }
    echo update();
}
?>