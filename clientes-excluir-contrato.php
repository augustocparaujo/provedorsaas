<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
require_once('routeros_api.class.php');
$mk = new RouterosAPI();
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true ){echo '<script>location.href="sair.php";</script>'; }

if(!empty($_GET['id'])){
    @$id = $_GET['id'];     
    //ACHAR CLIENTE ANTES DE ALTERAR BANCO E RB
    $sql0 = mysqli_query($conexao,"SELECT * FROM contratos WHERE id='$id'") or die (mysqli_error($conexao));
    $r = mysqli_fetch_array($sql0);
    $login = $r['login'];
    $plano = $r['plano'];

    //pegar dados do servidor
    $sql1 = mysqli_query($conexao,"SELECT plano.*, servidor.ip,user,password FROM plano
    LEFT JOIN servidor ON plano.servidor = servidor.id WHERE plano.id='$plano'");
    $rp = mysqli_fetch_array($sql1);
    $ipservidor = $rp['ip'];
    $user = $rp['user'];
    $passwords = $rp['password'];

    if($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
        $find = @$mk->comm("/ppp/secret/print", array("?name" =>  utf8_decode($login),));                                        
        //existe
        if (count($find) >= 1) {
            $Finduser  = $find[0];
            $find = $mk->comm("/ppp/secret/remove", array(".id" =>  $Finduser['.id']));

            $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));
            if (count($find) >= 1) {
                $Finduser  = $find[0];
                $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id']));
            }  
        }
    }                 

    mysqli_query($conexao,"DELETE FROM contratos WHERE id='$id'") or die (mysqli_error($conexao));

    echo delete();
}

?>