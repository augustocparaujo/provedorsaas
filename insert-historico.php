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

@$idcliente = $_POST['idcliente'];

if(!empty(@$idempresa)){
    $obs = AspasBanco($_POST['obs']);
        if(!empty($obs)){
            mysqli_query($conexao,"INSERT INTO historico (idempresa,idcliente,obs,usuariocad,datacad) 
            VALUES ('$idempresa','$idcliente','$obs','$nomeuser',NOW())") or die (mysqli_error($conexao));

//SELECT * FROM `historico` WHERE 1


            echo insert();

        }else{
            echo persona('Preencher campos obrigatórios');
        }

}else{
    echo persona('Algo deu errado :( !');
}
?>            