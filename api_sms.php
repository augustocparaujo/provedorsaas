<?php
// Instalação da biblioteca
// https://github.com/comtele/comtele-php-classic-sdk/blob/master/ComteleSDK-PHP-Classic.zip
require_once "comtele/textmessage_service.php";
//$API_KEY = "bfcd1f9c-059f-4ce4-ba12-1f25f1e7ccef";
//$conta = 68367;
//envio com curl
function enviaSms($idempresa, $contato, $mensagem, $usuario)
{
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));
    $dd = mysqli_fetch_array($query);
    $API_KEY = $dd['token_sms'];
    $conta = $dd['contasms'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sms.comtele.com.br/api/v2/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'Receivers=' . $contato . '&Content=' . $mensagem,
        CURLOPT_HTTPHEADER => array(
            'auth-key: ' . $API_KEY,
            'Content-Type: application/x-www-form-urlencoded',
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response, true);
    if ($json['Success'] == true) {
        //salvar no banco como historico apos envio
        //id	contato	textosms	data	usuario	
        mysqli_query($conexao, "INSERT INTO sms (contato,textosms,usuario) VALUES ('$contato','$mensagem','$usuario')") or die(mysqli_error($conexao));
        //return $json; //quantidade de sms
    } else {
        $mensa = $json['Message'];
        //return $json['Message'];
        mysqli_query($conexao, "INSERT INTO sms (contato,textosms,usuario) VALUES ('$contato','$mensa','Automático')") or die(mysqli_error($conexao));
    }
    /*resposta ok
    {
        "Success": true, 
        "Object": {
        "requestUniqueId": "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX"
        }
        "Message": "A requisicao de envio foi encaminhada para processamento com sucesso. Voce podera acompanhar o status pelos relatorios."
    }
    //resposta sem saldo
    {
        "Success": false,
        "Object": {},
        "Message": "A conta 68368 possui 0 credito(s), mas precisa de no minimo 1 credito(s) para poder enviar a mensagem."
    }*/
}
//saldo
function saldoSms($idempresa)
{
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));
    $dd = mysqli_fetch_array($query);
    $API_KEY = $dd['token_sms'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sms.comtele.com.br/api/v2/credits/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'auth-key: ' . $API_KEY,
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response, true);
    if ($json['Success'] == true) {
        $json =  $json['Object'];
        return $json; //quantidade de sms
    } else {
        return $json['Message'];
    }
    /*
    //resposat
    {
        "Success": true,
        "Object": 0,
        "Message": null
    }
    */
}
//echo json_encode(saldoSms($API_KEY,$conta));
//encurdado de link
function encurtaLink($idempresa, $url)
{
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));
    $dd = mysqli_fetch_array($query);
    $API_KEY = $dd['token_sms'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sms.comtele.com.br/api/v2/accounturls',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'Url=' . $url,
        CURLOPT_HTTPHEADER => array(
            'auth-key: ' . $API_KEY,
            'Content-Type: application/x-www-form-urlencoded',
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response, true);
    if ($json['Success'] == true) {
        echo 'Copiar: ' . $json['Object']['ShorterUrl'];
    } else {
        return 'Erro, Tente novamente';
    }
    /*resposta
        {
            "Success": true,
            "Object": {
                "Code": "446bab62",
                "OriginalUrl": "http://www.mktop.net",
                "UsageCount": 0,
                "UsageDate": "0001-01-01T00:00:00-02:00",
                "Status": "Valid",
                "ReceiveApprovalAlert": false,
                "ShorterUrl": "https://cmtl.io/446bab62"
            },
            "Message": "A url foi criada e enviada para aprovacao com sucesso."
        }
        */
}
