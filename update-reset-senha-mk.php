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

@$id = $_GET['id'];
if(!empty($id)){
    $novouser = md5('demo');
    $senha = AspasBanco(gerar_senha(6, true, true, true, true));
    $senhamd5 = md5($senha);

    mysqli_query($conexao,"UPDATE user SET user='$novouser',senha='$senhamd5' WHERE id='$id'") or die (mysqli_error($conexao));

    echo persona('Usuário: demo <br> Senha: '.$senha);
            
}else{
    echo persona('Erro! ');
}

?>