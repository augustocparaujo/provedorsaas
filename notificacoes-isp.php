<?php
//recebe informaçõess
header('Access-Control-Allow-Origin: *; Content-Type: application/json;');

############################### gerencianet ###############################
if(@$_GET['customid'] != ''){
  //$code = json_encode($_POST['notification']);
  //$code = substr($code,1);
  //$code = substr($code, 0, -1);
   $ncobranca = $_GET['customid'];
  //https://api.gerencianet.com.br/v1/notification/
  include('conexao.php');
  //token privado da empresa
  $query2 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE idempresa='999' AND recebercom='Gerencianet'") or die (mysqli_error($conexao));
  $retPrivado = mysqli_fetch_array($query2);
  //produção
  define("clienteid",$retPrivado['clienteid']);
  define("secretid",$retPrivado['clientesecret']);
  define("URL_API","https://api.gerencianet.com.br");

  function AcessoTokenG(){
    $url = URL_API.'/v1/authorize';
    $base64 = base64_encode(clienteid.':'.secretid);
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{"grant_type": "client_credentials"}',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic '.$base64,
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response);
    //print_r($response);
    return $json->access_token;
  }
  ################################################# CONSULTA BOLETO GNET ################################################
  //consultar -> /v1/charge/:id
  function consultaBoletoGerenciaNet($ncobranca){

    $token = AcessoTokenG();
    $url = URL_API.'/v1/charge/';
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url.$ncobranca,
      //CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_MAXREDIRS => 2,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $token",
        "Content-Type: application/json"
          ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response,true);
    //print_r($response);
    //baixa no banco caso já tenha recebido  
    if($json['code'] == 200){ //se sucesso na geração
    //reativação automatica do login
      //print $situacao;
      if($json['data']['status'] == 'paid' OR $json['data']['status'] == 'settled'){

        include('conexao.php'); 
        $query0 = mysqli_query($conexao,"SELECT * FROM user_cobranca WHERE ncobranca='$ncobranca'") or die (mysqli_error($conexao));
        $retCob = mysqli_fetch_array($query0);
        $idcliente = $retCob['idcliente'];

      mysqli_query($conexao,"UPDATE user_cobranca SET datapagamento=NOW(), situacao='RECEBIDO' WHERE ncobranca='$idcobranca'") or die (mysqli_error($conexao));
      mysqli_query($conexao,"UPDATE user SET situacao='1' WHERE id='$idcliente'") or die (mysqli_error($conexao)); 
      }
    }
  }

  
  echo consultaBoletoGerenciaNet($idcobranca);
}//fim gerencianet