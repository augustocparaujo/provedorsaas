<?php
function enviaNotificacao($nome, $contato, $msg, $appkey)
{

    $contato = "55" . $contato;
    $curl = curl_init();
    $body = [
        "number" => $contato,
        "body" => $msg,
    ];
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sophianet.com.br/api/messages/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $appkey",
            "Content-Type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response);
    //print_r($response);
    if (!empty($json->mensagem) == 'Mensagem enviada') {
        return 'sucesso';
    } else {
        return $json->error;
    }
}
