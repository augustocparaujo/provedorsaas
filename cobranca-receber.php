<?php
session_start();
include('conexao.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

function Moeda9($get_valor)
{
    $source = array('.', ',');
    $replace = array('', '.');
    $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
    if (empty($valor)) {
        return 0;
    } else {
        return $valor;
    } //retorna o valor formatado para gravar no banco
}; //moeda

function gerarToken2($entropy)
{
    $s = uniqid("", $entropy);
    $num = hexdec(str_replace(".", "", (string)$s));
    $index = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base = strlen($index);
    $out = '';
    for ($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
        $a = floor($num / pow($base, $t));
        $out = $out . substr($index, $a, 1);
        $num = $num - ($a * pow($base, $t));
    }
    return $out;
}


$id = $_POST['id'];
$cobranca = $_POST['cobranca'];
$banco = $_POST['banco'];
$tokenunico = gerarToken2(true);
$nomecliente = $_POST['nomecliente'];
$valor = Moeda9($_POST['valor']);

$cartaocredito = Moeda9($_POST['cartaocredito']);
$cartaodebito = Moeda9($_POST['cartaodebito']);
$pix = Moeda9($_POST['pix']);
$dinheiro = Moeda9($_POST['dinheiro']);
$valorpago = $cartaocredito + $cartaodebito + $pix + $dinheiro;

$datapagamento = date('Y-m-d', strtotime($_POST['datapagamento']));
$datapagamento2 = date('Y-m-d', strtotime($_POST['datapagamento']));

$tipo = 'Entrada';
$descricao = 'Recebimento de serviço de tecnologia (Recebido em carteira)';

if (!empty($id) and !empty($valorpago)) {

    if ($_POST['banco'] == 'Gerencianet') {
        include_once('api_gerencianet.php');
        receberCobranca($id);
    }

    if ($_POST['banco'] == 'Banco Juno') {
        include_once('api_juno.php');
        receberCobranca($id, $valorpago, $datapagamento);
    }

    if ($_POST['banco'] == 'Banco do Brasil') {
        include_once('api_bb.php');
        receberCobranca($id, $valorpago, $datapagamento, $nomeuser);
    }

    if ($_POST['banco'] == 'Carteira') {
        //tabela cobranca
        mysqli_query($conexao, "UPDATE cobranca SET valorpago='$valorpago',datapagamento='$datapagamento',situacao='BAIXADO' WHERE id='$id'") or die(mysqli_error($conexao));

        #########################################################################################################
        $sqlV = mysqli_query($conexao, "SELECT idcliente FROM cobranca WHERE id='$id'") or die(mysqli_error($conexao));
        if (mysqli_num_rows($sqlV) >= 1) {
            while ($ddv = mysqli_fetch_array($sqlV)) {
                $idcliente = $ddv['idcliente'];
                $sqlV = mysqli_query($conexao, "SELECT login,plano FROM contratos WHERE idcliente='$idcliente'") or die(mysqli_error($conexao));
                if (mysqli_num_rows($sqlV) >= 1) {
                    while ($ddc = mysqli_fetch_array($sqlV)) {
                        $login = $ddc['login'];
                        $plano = $ddc['plano'];
                        //servidor
                        $sql2 = mysqli_query($conexao, "SELECT plano.*, servidor.ip,servidor.user,servidor.password FROM plano 
                        LEFT JOIN servidor ON plano.servidor = servidor.id
                        WHERE plano.id='$plano'");
                        $dds = mysqli_fetch_array($sql2);
                        $planonome = $dds['plano'];
                        $user = $dds['user'];
                        $password = $dds['password'];

                        require_once('routeros_api.class.php');
                        $mk = new RouterosAPI();
                        //trocar profile para reduzido
                        if ($mk->connect($ip, decrypt($user), decrypt($password))) {
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
                    }
                }
            }
        }
        #########################################################################################################
    }

    //teabela caixa
    mysqli_query($conexao, "INSERT INTO caixa (banco,titulo,nrecibo,idempresa,nomecliente,tipo,descricao,valor,valorpago,dinheiro,cartaocredito,cartaodebito,pix,data,datapagamento,user)
    VALUES ('$banco','$cobranca','$tokenunico','$idempresa','$nomecliente','$tipo','$descricao','$valor','$valorpago','$dinheiro','$cartaocredito','$cartaodebito','$pix',NOW(),'$datapagamento2','$nomeuser')
    ") or die(mysqli_error($conexao));
} else {
    echo persona('Preencher campos obrigatórios.');
}
