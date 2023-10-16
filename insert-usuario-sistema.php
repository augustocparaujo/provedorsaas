<?php
session_start();
include('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$logomarcauser = $_SESSION['logomarcauser'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

@$nome = $_POST['nome'];
@$logintxt = $_POST['login'];
@$user = md5($_POST['login']);
@$senha = md5($_POST['senha']);
@$idempresa = date('YmdHms') . mt_rand(5, 15);
$tipo = 'Admin';

if (!empty($nome) and !empty($user)) {
    $sql = mysqli_query($conexao, "SELECT * FROM user WHERE nome = '$nome' AND login = '$logintxt' AND user = '$user'");
    if (mysqli_num_rows($sql) == 0) {

        mysqli_query($conexao, "INSERT INTO user (idempresa,tipo,nome,user,senha,datacadastro,usuariocad,situacao) 
        VALUES ('$idempresa','$tipo','$nome','$user','$senha',NOW(),'$nomeuser',1)") or die(mysqli_error($conexao));

        echo insert();
    } else {

        echo persona('Nome de usuaário indisponível, tente outro');
    }
} else {
    echo persona('Preencher campos obrigatórios.');
}
