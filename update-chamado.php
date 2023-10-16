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

$id = $_POST['id'];
$tipo = $_POST['tipo'];
$idcliente = $_POST['idcliente'];
$idcontrato = $_POST['idcontrato'];
$nchamado = date('dmYHms').'-'.$idcliente;
$obs = AspasBanco($_POST['obs']);
$dataatendimento = date($_POST['dataatendimento']);
$situacao = $_POST['situacao'];
$idtecnico = $_POST['idtecnico'];


if(!empty($id)){
    mysqli_query($conexao,"UPDATE chamado SET idcontrato='$idcontrato',tipo='$tipo',obs='$obs',dataatendimento='$dataatendimento',situacao='$situacao' WHERE id='$id'")
    or die (mysqli_error($conexao));

    if(!empty($idtecnico)){
        $sql = mysqli_query($conexao,"SELECT * FROM usuarios WHERE id='$idtecnico'") or die (mysqli_error($conexao));
        $r = mysqli_fetch_array($sql);
        $nometecnico = $r['nome'];
        $idtecnico = $r['id'];

        mysqli_query($conexao,"UPDATE chamado SET idtecnico='$idtecnico',nometecnico='$nometecnico' WHERE id='$id'") or die (mysqli_error($conexao));

    }

    mysqli_query($conexao,"INSERT INTO log_chamado (nchamado,usuariocad,datacad,obs,situacao) 
    VALUES('$nchamado','$nomeuser',NOW(),'$obs','$situacao')") or die (mysqli_error($conexao));

    echo update();
    
}else{
    echo persona('Preencher campos obrigatórios.');
}

?>