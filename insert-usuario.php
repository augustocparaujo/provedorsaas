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

$nome = $_POST['nome'];
@$logintxt = $_POST['login'];
@$user = md5($_POST['login']);
@$senha = md5($_POST['senha']);
$situacao = 1;

if (!empty($nome) and !empty($user)) {

    $sql = mysqli_query($conexao, "SELECT * FROM usuarios WHERE nome = '$nome' AND login = '$user'");
    if (mysqli_num_rows($sql) == 0) {

        mysqli_query($conexao, "INSERT INTO usuarios (idempresa,nome,logintxt,login,senha,logomarca,datacad,usuariocad,situacao) 
        VALUES ('$idempresa','$nome','$logintxt','$user','$senha','$logomarcauser',NOW(),'$nomeuser','$situacao')");

        echo insert();
    } else {
        echo persona('Usuário já existente, tente outro');
    }
} else {
    echo persona('Preencher campos obrigatórios.');
}
