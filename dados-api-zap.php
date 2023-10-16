<?php
session_start();
include('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a�o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

//idempresa,tokenprivado,clienteid,clientesecret,recebercom,diagerar,aposvencimento,multaapos,jurosapos,diasbloqueio,bloqueioautomatico,data,user	
@$id = $_POST['id'];
@$api = "douglas";
@$instancia = AspasBanco($_POST['numero']);
@$token = AspasBanco($_POST['token']);
@$antes = $_POST['antes'];
@$depois = $_POST['depois'];
@$texto = AspasBanco($_POST['texto']);

//idempresa,usuariosms,senhasms,antes,depois,texto,api,token,instancia


if ($id == '') {        //verifica se dados obrigatorios estão preenchidos

    mysqli_query($conexao, "INSERT INTO config_sms (idempresa,antes,depois,texto,api,token,instancia) 
    VALUES ('$idempresa','$antes','$depois','$texto','$api','$token','$instancia')")
        or die(mysqli_error($conexao));

    echo insert();
} else {

    mysqli_query($conexao, "UPDATE config_sms SET 
    idempresa='$idempresa',antes='$antes',depois='$depois',texto='$texto',api='$api',token='$token',instancia='$instancia' WHERE id='$id'") or die(mysqli_error($conexao));
    echo update();
}
