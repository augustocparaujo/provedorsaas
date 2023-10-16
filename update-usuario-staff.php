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

//idempresa,nome,email,cpf,contato,logintxt,login,senha,logomarca,situacao,datacad,usuariocad

@$id = $_POST['id'];
@$nome = $_POST['nome'];
@$email = $_POST['email'];
@$cpf = limpaCPF_CNPJ($_POST['cpf']);
@$contato = limpaCPF_CNPJ($_POST['contato']);

if($_POST['login'] != ''){ $login = md5($_POST['login']); } else { $login = '';}
if($_POST['senha'] != ''){ $senha = md5($_POST['senha']); } else { $senha = '';}

if(!empty($id) || !empty($nome) || !empty($contato)){

    //atualizar campos comuns
    mysqli_query($conexao,"UPDATE usuarios SET nome='$nome',email='$email',cpf='$cpf',contato='$contato',logomarca='$logomarcauser' WHERE id='$id'") 
    or die (mysqli_error($conexao));

    //se a senha vir preenchido
    if($senha != ''){
        mysqli_query($conexao,"UPDATE usuarios SET senha='$senha' WHERE id='$id'") or die (mysqli_error($conexao));
    }
    //se a login vir preenchido
    if($login != ''){
        mysqli_query($conexao,"UPDATE usuarios SET login='$login',logintxt='$_POST[login]' WHERE id='$iduser'") or die (mysqli_error($conexao));
    }       
}else{
    echo persona('Campos obrigatórios!');
}

?>