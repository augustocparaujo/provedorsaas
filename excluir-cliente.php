<?php
session_start();
//sem limite de tempo
set_time_limit(0);
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

if (isset($_GET['id']) and isset($_SESSION['idempresa'])) {
    $id = $_GET['id'];

    //dados cliente para excluir e derrubar do server
    $sql = mysqli_query($conexao, "SELECT * FROM contratos WHERE idcliente='$id'") or die(mysqli_error($conexao));
    while ($retorno = mysqli_fetch_array($sql)) {
        $login = $retorno['login'];
        $plano = $retorno['plano'];
        //nome plano
        $queryplano = mysqli_query($conexao, "SELECT plano.plano,servidor,servidor.id,ip,user,password 
            FROM plano LEFT JOIN servidor ON plano.servidor = servidor.id
            WHERE plano.id='$plano'");
        $retorno2 = mysqli_fetch_array($queryplano);
        $ipservidor = $retorno2['ip'];
        $user = $retorno2['user'];
        $passwords = $retorno2['password'];

        //conectar no servidor
        require_once('routeros_api.class.php');
        $mk = new RouterosAPI();

        //remover do secret
        if ($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
            //dados acesso
            $find = @$mk->comm("/ppp/secret/print", array(
                "?name" =>  utf8_decode($login),
            ));

            //existe
            if (count($find) >= 1) {
                //pedir comentario
                $Finduser  = $find[0];
                $find = $mk->comm("/ppp/secret/remove", array(
                    ".id" =>  $Finduser['.id'],
                ));
            }
        }

        //derrubar
        if ($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
            //buscar user o active
            $find = @$mk->comm("/ppp/active/print", array(
                "?name" =>  utf8_decode($login),
            ));
            //verificar se tem
            if (count($find) >= 1) {
                $Finduser  = $find[0];
                //remover
                $find = $mk->comm("/ppp/active/remove", array(
                    ".id" =>  $Finduser['.id'],
                ));
            }
        }
    } //fim while

    //últma execução
    mysqli_query($conexao, "DELETE FROM cliente WHERE id='$id'");
    mysqli_query($conexao, "DELETE FROM contratos WHERE idcliente='$id'");
    mysqli_query($conexao, "DELETE FROM cobranca WHERE idcliente='$id'");
    mysqli_query($conexao, "DELETE FROM chamado WHERE idcliente='$id'");
    mysqli_query($conexao, "DELETE FROM caixa WHERE idcliente='$id'");
    mysqli_query($conexao, "DELETE FROM historico WHERE idcliente='$id'");
    mysqli_query($conexao, "DELETE FROM log_chamado WHERE idcliente='$id'");
    mysqli_query($conexao, "DELETE FROM log_cobranca WHERE idcliente='$id'");

    echo update();
} else {
    echo persona('Algo deu errado!');
}
