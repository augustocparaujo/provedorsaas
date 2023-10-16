<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
include_once('routeros_api.class.php');

$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

$nome = $_POST['nome'];
$ipservidor = $_POST['ip'];
$usuario = AspasBanco(encrypt($_POST['usuario']));
$senha = AspasBanco(encrypt($_POST['senha']));
$situacao = 'ativo';

//idempresa,nome,ip,user,password,datacadastro,situacao	

if(!empty($nome) || !empty($ip) || !empty($usuario) || !empty($senha)){

        mysqli_query($conexao,"INSERT INTO servidor (idempresa,nome,ip,user,password,datacadastro,situacao) 
        VALUES ('$idempresa','$nome','$ipservidor','$usuario','$senha',NOW(),'ativo')");


        if ($error = mysqli_error($conexao)) {
            echo persona($error);
        }else{    
            echo insert();
        }
            
}else{
    echo persona('Preencher campos obrigatórios.');
}

?>