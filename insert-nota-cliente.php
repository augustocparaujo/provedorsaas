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

$idcliente = $_POST['idcliente'];
$descricao = AspasBanco('N°: '.$_POST['numeronota'].' | '.$_POST['descricao']);
$vencimento = dataBanco($_POST['vencimento']);

if(!empty(@$idcliente) AND !empty(@$idempresa)){    
    if($_FILES['arquivo']['name'] != ''){

        $diretorio = "notas/";
        $extensao = strrchr($_FILES['arquivo']['name'],'.');
        $novonome = mb_strtolower(md5(uniqid(rand(), true)).$extensao);
        $arquivo_tmp = $_FILES['arquivo']['tmp_name']; 
        move_uploaded_file($arquivo_tmp, $diretorio.$novonome);
          
        mysqli_query($conexao,"INSERT INTO notas (idcliente,idempresa,descricao,vencimento,nota) VALUES ('$idcliente','$idempresa','$descricao','$vencimento','$novonome')") or die (mysqli_error($conexao));
            
        echo update();
    } 
 
}else{
    echo persona('Algo deu errado :( !');
}

?>