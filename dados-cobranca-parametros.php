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

@$id = $_POST['id'];
@$aposvencimento = $_POST['aposvencimento'];
@$diasdesconto = $_POST['diasdesconto'];
@$valordesconto = Moeda($_POST['valordesconto']);
@$multaapos = Moeda($_POST['multaapos']);
@$jurosapos = Moeda($_POST['jurosapos']);
@$diasbloqueio = $_POST['diasbloqueio'];
@$bloqueioautomatico = $_POST['bloqueioautomatico'];


if ($id == '') {        //verifica se dados obrigatorios estão preenchidos

    mysqli_query($conexao, "INSERT INTO dadoscobranca (
    idempresa,aposvencimento,valordesconto,diasdesconto,multaapos,jurosapos,diasbloqueio,bloqueioautomatico,data,user) 
    VALUES (
    '$idempresa','$aposvencimento','$valordesconto','$diasdesconto','$multaapos','$jurosapos','$diasbloqueio','$bloqueioautomatico',NOW(),'$nomeuser')")
        or die(mysqli_error($conexao));

    echo insert();
} else {

    mysqli_query($conexao, "UPDATE dadoscobranca SET 
    idempresa='$idempresa',
    aposvencimento='$aposvencimento',
    valordesconto='$valordesconto',
    diasdesconto='$diasdesconto',
    multaapos='$multaapos',
    jurosapos='$jurosapos',
    diasbloqueio='$diasbloqueio',
    bloqueioautomatico='$bloqueioautomatico',
    atualizado=NOW() WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));

    echo update();
}
