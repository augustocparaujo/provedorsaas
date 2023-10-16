<?php
include('conexao.php'); 
require_once('routeros_api.class.php');
$mk = new RouterosAPI();

$id = $_GET['id'];
//plano, login
$sql = mysqli_query($conexao,"SELECT * FROM contratos WHERE id='$id'") or die (mysqli_error($conexao));
$ddc = mysqli_fetch_array($sql);
$login = utf8_decode($ddc['login']);
$plano = $ddc['plano'];

//servidor
$sql2 = mysqli_query($conexao,"SELECT plano.*, servidor.ip,user,password FROM plano 
LEFT JOIN servidor ON plano.servidor = servidor.id
WHERE plano.id='$plano'");
$dds = mysqli_fetch_array($sql2);

if($mk->connect($dds['ip'], decrypt($dds['user']), decrypt($dds['password']))){
    $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));             
    if (count($find) >= 1) {
        foreach ($find as $key => $value) {  
            echo'<div class="alert alert-success" style="width:250px !important">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
            <i class="fa fa-check"></i>Conectado<br />
            <strong>
            IP: '.$find[$key]['address'].'<br />
            Tempo:'.$find[$key]['uptime'].'<br />
            Status: online
            </strong>
            </div>';
        }               
    } else {  
        echo'<div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
        <i class="fa fa-exclamation-triangle"></i> <strong> PPPOE desconectado! </strong>
        </div>';}
} else {  
    echo'<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
    <i class="fa fa-exclamation-triangle"></i> <strong> Falha na conexão! </strong>
    </div>'; }
?>