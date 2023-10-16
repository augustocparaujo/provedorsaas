<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
require_once('routeros_api.class.php');
$mk = new RouterosAPI();

$idempresa = $_SESSION['idempresa'];
$logomarcauser = $_SESSION['logomarcauser'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }

//id cliente
$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM contratos WHERE idcliente='$id'") or die(mysqli_error($conexao));
while($ret = mysqli_fetch_array($query)){
    $login = AspasBanco($ret['login']);
    //plano cliente
    $plano = $ret['plano'];
    $queryplano = mysqli_query($conexao,"SELECT plano.plano,servidor,servidor.id,ip,user,password 
    FROM plano LEFT JOIN servidor ON plano.servidor = servidor.id
    WHERE plano.id='$plano'") or die (mysqli_error($conexao));
    $retorno = mysqli_fetch_array($queryplano);
    $ipservidor = $retorno['ip'];
    $user = $retorno['user'];
    $passwords = $retorno['password'];


    if($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
        $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));                                        
        //existe
        if (count($find) >= 1) {
            $Finduser  = $find[0];
            $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
        } 
        echo persona('Cliente derrubado'); 
    } 
}
        

?>