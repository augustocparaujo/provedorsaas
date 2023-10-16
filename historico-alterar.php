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

@$id = $_POST['id'];
$obs = AspasBanco($_POST['obs']);

$query0 = mysqli_query($conexao,"SELECT historico.obs FROM historico WHERE id='$id'");
$reto = mysqli_fetch_array($query0);
$obs2 = 'Antes: '.$reto['obs'].'<br> Depois: '.$obs;

if(!empty(@$idempresa)){
    
        if(!empty($obs)){
            mysqli_query($conexao,"UPDATE historico SET obs='$obs2',usuarioatualizou='$nomeuser',dataatualizacao=NOW()
            WHERE id='$id'") or die (mysqli_error($conexao));

            echo update();

        }else{
            echo persona('Preencher campos obrigatórios');
        }

}else{
    echo persona('Algo deu errado :( !');
}
?>            