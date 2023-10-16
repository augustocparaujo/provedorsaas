<?php
echo'Realizando desbloqueio <br /> <i style="color:red">Aguarde realizando processo...</i><hr>';
set_time_limit(0);
include('conexao.php'); 
include('funcoes.php');
require_once('routeros_api.class.php');
$mk = new RouterosAPI();
@$idempresa = $_GET['idempresa'];
$sql = mysqli_query($conexao,"SELECT cobranca.*, contratos.id as contratoid,contratos.plano,contratos.nomeplano,contratos.login,contratos.situacao, plano.servidor, servidor.ip,servidor.user,servidor.password FROM cobranca
LEFT JOIN contratos ON cobranca.idcliente = contratos.idcliente
LEFT JOIN plano ON contratos.plano = plano.id
LEFT JOIN servidor ON plano.servidor = servidor.id
WHERE cobranca.idempresa='$idempresa' ORDER BY cobranca.cliente ASC") or die (mysqli_error($conexao));
$row = mysqli_num_rows($sql);
   $n =1;
    while($ret = mysqli_fetch_array($sql)){ 
    if(@$contrato != $ret['contratoid']){
    $idcliente = @$ret['idcliente'];
    $plano = @$ret['plano'];
    echo $planonome = @$ret['nomeplano']; echo' |';
    echo $login = @$ret['login']; echo' |';
    echo $servidor = @$ret['servidor']; echo' |';
    echo $ip = @$ret['ip'];echo' <br />';
    $user = @$ret['user'];
    $password = @$ret['password'];

        //trocar profile para reduzido
        if($mk->connect($ip, decrypt($user), decrypt($password))) {
            $find = @$mk->comm("/ppp/secret/print", array("?name" =>  utf8_decode($login))); 
            if (count($find) >= 1) {
                $Finduser  = $find[0];
                $find = $mk->comm("/ppp/secret/set", array(
                    ".id" =>  $Finduser['.id'],
                    "name" => utf8_decode($login),
                    "profile" => utf8_decode($planonome),
                ));
                $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));
                if (count($find) >= 1) {
                    $Finduser  = $find[0];
                    $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                }
            }
        }
        
       
        //alterar situação tabela contratos e cliente
        mysqli_query($conexao,"UPDATE cliente SET situacao='Ativo' WHERE id='$idcliente'") or die (mysqli_error($conexao));
        mysqli_query($conexao,"UPDATE contratos SET situacao='Ativo' WHERE idcliente='$idcliente'") or die (mysqli_error($conexao));
 
        //exibir os alterados
        echo 'N°: '.$n.' / idcliente: '.$ret['idcliente'].' Cliente: '.$ret['cliente'].' / Plano: '.$planonome.' -> <i style="color:green">Reativado</i><hr>';

        $n++;
    }
    $contrato = $ret['contratoid'];
    }

echo'<i style="color:red">Finalizado processo...</i><hr>';
?>