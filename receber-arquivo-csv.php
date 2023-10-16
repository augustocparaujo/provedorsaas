<?php
session_start();
include('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

if (strtolower(substr($_FILES['file']['name'], -4)) == '.csv') {
    if (!empty(@$iduser) and !empty(@$idempresa)) {
        if ($_FILES['file']['name'] != '') {

            $diretorio = "tabelas/";
            $extensao = strrchr($_FILES['file']['name'], '.');
            $novonome = $idempresa . $extensao;
            $arquivo = $_FILES['file']['tmp_name'];
            move_uploaded_file($arquivo, $diretorio . $novonome);

            echo update();
        }
    } else {
        echo persona('Algo deu errado :( !');
    }
} else {
    echo persona('Arquivo precisa ser .csv, separado por virgula');
}
