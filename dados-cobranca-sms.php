<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a�o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

@$usuariosms = $_POST['usuariosms'];
@$senhasms = $_POST['senhasms'];

$query = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
    //se não existir
    if(mysqli_num_rows($query) == 0){
        //verifica se dados obrigatorios estão preenchidos
        mysqli_query($conexao,"INSERT INTO dadoscobranca (idempresa,usuariosms,senhasms) VALUES ('$idempresa','$usuariosms','$senhasms')");
            
        echo insert();        
    //se sim
    }else{
            $dd = mysqli_fetch_array($query);
            $id = $dd['id'];

            mysqli_query($conexao,"UPDATE dadoscobranca SET 
            idempresa='$idempresa',usuariosms='$usuariosms',senhasms='$senhasms',atualizado=NOW() WHERE idempresa='$idempresa'");
                
            echo update();
    }

?>