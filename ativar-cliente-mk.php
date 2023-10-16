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


//tipo,logomarca,cargo,nome,fantasia,cpf_cnpj,rg,email,nascimento,contato,contato2,cep,rua,bairro,cidade,estado,user,senha,datacadastro,situacao,obs

@$id = $_GET['id'];
if($_GET['s'] == 1){ $s = 1; }else{ $s = 0;}

    $sql = mysqli_query($conexao,"SELECT idempresa FROM user WHERE id='$id'");
    $ret = mysqli_fetch_array($sql);
    $iddesativar = $ret['idempresa'];

    mysqli_query($conexao,"UPDATE usuarios SET situacao='$s' WHERE idempresa='$iddesativar'");

    //atualizar campos comuns
    mysqli_query($conexao,"UPDATE user SET situacao='$s' WHERE id='$id'") 
    or die (mysqli_error($conexao));

echo update();

?>