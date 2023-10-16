<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
include_once('routeros_api.class.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

@$login = $_GET['login'];
$servidor = $_GET['id'];
$sql = mysqli_query($conexao,"SELECT * FROM servidor WHERE id='$servidor'");
$reto = mysqli_fetch_array($sql);
$ip = $reto['ip'];
$user = $reto['user'];
$pw = $reto['password'];


    require_once('routeros_api.class.php');
    $mk = new RouterosAPI();
   if($mk->connect($ip, decrypt($user), decrypt($pw))){   

        $find2 = $mk->comm("/interface/monitor-traffic", array(
        "interface" => "<pppoe-".$login.">",
        "once" => "",
        ));
        foreach ($find2 as $key1 => $value) {
            echo'
            <div class="col-lg-12">
            Donwload <i class="fa fa-arrow-down text-blue">: '.formatBytes(@$find2[$key1]['tx-bits-per-second']).'</i>
            </div>
            <div class="col-lg-12">
            Upload <i class="fa fa-arrow-up text-red">: '.formatBytes(@$find2[$key1]['rx-bits-per-second']).'</i>
            </div>
            ';
        }
    }else{
	echo'Sem conexão com servidor';

}

?>