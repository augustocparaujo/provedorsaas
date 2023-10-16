<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if (isset($_SESSION['iduser']) != true || empty($_SESSION['iduser'])) {
        echo '<script>location.href="sair.php";</script>';
}


@$empresa = AspasBanco($_POST['empresa']);
echo isset($_POST['novacto']);
if (isset($_POST['novacto']) == true and !empty($_POST['novacto'])) {
        @$cto = AspasBanco(@$_POST['novacto']);
} else {
        @$cto = AspasBanco(@$_POST['cto']);
}
@$porta = AspasBanco($_POST['porta']);
@$cliente = AspasBanco($_POST['cliente']);
@$longitude = AspasBanco($_POST['longitude']);
@$latitude = AspasBanco($_POST['latitude']);
@$estado        = AspasBanco($_POST['estado']);
@$localizacao = AspasBanco($_POST['localizacao']);

if (isset($_POST['id']) == true and !empty($_POST['id'])) {

        mysqli_query($conexao, "UPDATE controle_cto SET 
        empresa='$empresa',cto='$cto',porta='$porta',cliente='$cliente',longitude='$longitude',latitude='$latitude',estado='$estado',localizacao='$localizacao'
        WHERE id = '$_POST[id]'");

        echo persona('Alterado com sucesso');
} elseif (isset($_GET['destroy']) == true && !empty($_GET['destroy']) and isset($_GET['id']) == true and !empty($_GET['id'])) {
        mysqli_query($conexao, "DELETE FROM controle_cto WHERE id = '$_GET[id]'");

        echo delete('Excluido com sucesso');
} else {
        mysqli_query($conexao, "INSERT INTO controle_cto 
        (idempresa,empresa,cto,porta,cliente,longitude,latitude,estado,localizacao)
VALUES ('$idempresa','$empresa','$cto','$porta','$cliente','$longitude','$latitude','$estado','$localizacao')
        ") or die(mysqli_error($conexao));

        echo persona('Cadastrado com sucesso');
}
