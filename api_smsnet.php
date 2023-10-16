<?php
//Desenvolvido por André Melo
//Alterações por Augusto Araujo
ob_start();
@session_start();
include('conexao.php'); 
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a�o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

$sql = mysqli_query($conexao,"SELECT usuariosms,senhasms FROM dadoscobranca WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($sql);
#################################
define('USUARIOSMS', $dd['usuariosms']); //valor da multa
define('SENHASMS', $dd['senhasms']);

    function enviaSms($contato,$msg)
    {   
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sistema.smsnet.com.br/sms/global?username='.USUARIOSMS.'-6&password='.SENHASMS.'&to&to=55'.$contato.'&msg='.$msg,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true);
        //print_r($response);
    }

    //saldo
    function saldoSms()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sistema.smsnet.com.br/api/credits/balance?username='.USUARIOSMS.'&password='.SENHASMS,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            //CURLOPT_HTTPHEADER => array( ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true);
        //print_r($json);
        if ($json['success'] == 1) {
            $json =  $json['balance'];
            return $json; //quantidade de sms
        } else {
            return 'Sem acesso';
        }
    }