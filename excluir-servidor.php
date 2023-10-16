<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }



$id = $_GET['id'];

if(!empty($id)){

    $query = mysqli_query($conexao,"SELECT * FROM plano WHERE servidor='$id'") or die(mysqli_error($conexao));
    if(mysqli_num_rows($query) == 0){

        mysqli_query($conexao,"DELETE FROM servidor WHERE id='$id'") or die (mysqli_error($conexao));
        echo delete();

    }else{
        echo persona('Existe plano nesse servidor');
    }
            
}else{
    echo persona('Falta informação!');
}

?>