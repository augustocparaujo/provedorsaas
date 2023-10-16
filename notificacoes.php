<?php
//recebe informaçõess
header('Access-Control-Allow-Origin: *; Content-Type: application/json;');

############################### gerencianet ###############################
if(@$_GET['customid'] != ''){
  //$code = json_encode($_POST['notification']);
  //$code = substr($code,1);
  //$code = substr($code, 0, -1);
   $customid = $_GET['customid'];
  //https://api.gerencianet.com.br/v1/notification/
  include('conexao.php');
  $query0 = mysqli_query($conexao,"SELECT * FROM cobranca WHERE custom_id='$customid' AND banco='Gerencianet'") or die (mysqli_error($conexao));
  $retCob = mysqli_fetch_array($query0);
  $idcobranca = $retCob['ncobranca'];
  $idcliente = $retCob['idcliente'];

  //token privado da empresa
  $query2 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE recebercom='Gerencianet'") or die (mysqli_error($conexao));
  $retPrivado = mysqli_fetch_array($query2);
  $cliente_id = $retPrivado['clienteid'];
  $cliente_secret = $retPrivado['clientesecret'];

  //teste
  //define('clienteid',$cliente_id);
  //define('secretid',$cliente_secret);
  //define('URL_API','https://sandbox.gerencianet.com.br');

  //produção
  define("clienteid",$cliente_id);
  define("secretid",$cliente_secret);
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
  function consultaBoletoGerenciaNet($idcobranca){
    include('conexao.php'); 
    $query0 = mysqli_query($conexao,"SELECT * FROM cobranca WHERE ncobranca='$idcobranca' AND banco='Gerencianet'") or die (mysqli_error($conexao));
    $retCob = mysqli_fetch_array($query0);
    $idcliente = $retCob['idcliente'];
    $nomecliente = $retCob['cliente'];

    $token = AcessoTokenG();
    $url = URL_API.'/v1/charge/';
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url.$idcobranca,
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
      //status
      switch ($json['data']['status']) {
        case 'waiting':
            $situacao = 'PENDENTE';
            $valorpago = 0.00;
            break;
        case 'unpaid':
            $situacao = 'PENDENTE';
            $valorpago = 0.00;
            break;
        case 'paid':
            $situacao = 'RECEBIDO';
            $valorcorrigido = $json['data']['total']/100;
            $valorpago = number_format((float)$valorcorrigido, 2, '.', '');
            break;
        case 'settled':
            $situacao = 'RECEBIDO';
            $valorcorrigido = $json['data']['total']/100;
            $valorpago = number_format((float)$valorcorrigido, 2, '.', '');
            break;
        case 'canceled':
            $situacao = 'CANCELADO';
            $valorpago = 0.00;
        break;
      } 
      
    //reativação automatica do login
      //print $situacao;
      if($situacao == 'RECEBIDO'){
      mysqli_query($conexao,"UPDATE cobranca SET valorpago='$valorpago',datapagamento=NOW(), situacao='$situacao' WHERE ncobranca='$idcobranca'") or die (mysqli_error($conexao));
 
      //alimeNta o caixa
      mysqli_query($conexao,"INSERT INTO caixa (banco,tipo,nomecliente,descricao,valor,valorpago,pix,data,datapagamento,user) 
      VALUES ('Gerencianet','ENTRADA','$nomecliente','BOLETO','$valorpago','$valorpago','$valorpago',NOW(),NOW(),'BAIXA SISTEMA')") 
      or die (mysqli_error($conexao));
      //echo persona('Recebido com sucesso');

     
        function AspasBanco2($string){
          $string = str_replace(chr(146).chr(146),'"', $string);
          $string = str_replace(chr(146),"'",$string);
          return addslashes($string);
        };
        
          $query = mysqli_query($conexao,"SELECT * FROM contratos WHERE idcliente='$idcliente'") or die(mysqli_error($conexao));
          while($ret = mysqli_fetch_array($query)){
            if($ret['situacao'] == 'Bloqueado'){

            $login = AspasBanco2($ret['login']);
            //plano cliente
            $plano = $ret['plano'];
            $queryplano = mysqli_query($conexao,"SELECT plano.plano,servidor,servidor.id,ip,user,password 
            FROM plano LEFT JOIN servidor ON plano.servidor = servidor.id
            WHERE plano.id='$plano'");
            $retorno = mysqli_fetch_array($queryplano);
            $nomeplano = $retorno['plano'];
            $idservidor = $retorno['servidor'];
            $ipservidor = $retorno['ip'];
            $user = $retorno['user'];
            $passwords = $retorno['password'];
            require_once('routeros_api.class.php');
            $mk = new RouterosAPI();
            if($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
                $find = @$mk->comm("/ppp/secret/print", array("?name" =>  utf8_decode($login),));                                        
                //existe
                if (count($find) >= 1) {  
                    $Finduser  = $find[0];
                    $find = $mk->comm("/ppp/secret/set", array(
                        ".id" =>  $Finduser['.id'],
                        "profile" =>  utf8_decode(AspasBanco2($nomeplano)),
                    ));
                    $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));
                    if (count($find) >= 1) {
                        $Finduser  = $find[0];
                        $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                    }             
                }
            }
            mysqli_query($conexao,"UPDATE contratos SET situacao='ATIVO' WHERE idcliente='$idcliente'") or die (mysqli_error($conexao));
          }

          }
          mysqli_query($conexao,"UPDATE cliente SET situacao='ATIVO', atualizado=NOW() WHERE id='$idcliente'") or die (mysqli_error($conexao)); 
      }
    }
  }
  
  echo consultaBoletoGerenciaNet($idcobranca);
}//fim gerencianet



############################### baixa juno ###############################

function AccessToken($idempresa){  
  include('conexao.php'); 
    //token privado da empresa
    $query2 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND recebercom='Banco Juno'") or die (mysqli_error($conexao));
    $retPrivado = mysqli_fetch_array($query2);
    $tokenprivado = $retPrivado['tokenprivado']; 
    $cliente_id = $retPrivado['clienteid'];
    $cliente_secret = $retPrivado['clientesecret'];
  
    //desenvolcido por André R. Melo
    define('JUNO_CLIENT_ID', $cliente_id); 
    define('JUNO_CLIENT_SECRET', $cliente_secret);
    //define('JUNO_OAUTH2', 'https://sandbox.boletobancario.com/authorization-server/oauth/token');
    define('JUNO_OAUTH2', 'https://api.juno.com.br/authorization-server/oauth/token');

    //gera o token
    $scope = "all";
    $post = array(
        'grant_type' => 'client_credentials', 
        'scope' => $scope,
        "expires_in" => 3600
    );    
    $base64 = base64_encode(JUNO_CLIENT_ID. ':' .JUNO_CLIENT_SECRET);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, JUNO_OAUTH2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization: Basic ' . $base64,
        'Content-Type: application/x-www-form-urlencoded'
    ));
    //tratar resposta
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response);
    //print_r($response);
    $access_token = @$json->access_token;    

    return $access_token;
} 

if(!empty($_GET['chargeCode']) OR !empty($_POST['chargeCode'])){
  if(!empty($_GET['chargeCode'])){ $id = $_GET['chargeCode']; }else{ $id = $_POST['chargeCode']; }
  include('conexao.php'); 
  $query = mysqli_query($conexao,"SELECT cobranca.*, cliente.nome FROM cobranca 
  LEFT JOIN cliente ON cobranca.idcliente = cliente.id
  WHERE cobranca.code='$id' AND cobranca.banco='Banco Juno'") or die (mysqli_error($conexao));
  $ddcc = mysqli_fetch_array($query);
  $code = $ddcc['idcobranca'];
  $nomecliente = $ddcc['cliente'];
  $idempresa = $ddcc['idempresa'];
  
    $query2 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND recebercom='Banco Juno'") or die (mysqli_error($conexao));
    $retPrivado = mysqli_fetch_array($query2);
    $tokenprivado = $retPrivado['tokenprivado'];  

    $token = AccessToken($idempresa);
    if(!empty(@$token)){
        //$url = 'https://sandbox.boletobancario.com/api-integration/charges/';
        $url = 'https://api.juno.com.br/api-integration/charges/';
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url.$code,
        //CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => 'GET',
        //CURLOPT_POSTFIELDS => json_encode($dados),
        CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token,
        'X-Api-Version: 2',
        'X-Resource-Token:'.$tokenprivado,
        'Content-Type: application/json;charset=UTF-8'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response,true);
        //baixa no banco caso já tenha recebido
        print_r($json);
      if($json['status'] == 'PAID' OR $json['status'] == 'MANUAL_RECONCILIATION'){
          //resposta do pagamento
          $json2 = $json['payments'];
          //percore o array para ver a data dop pagamento
          //foreach ($json2 as $item) { 
          //datapagamento
          if(!empty($item['releaseDate'])){ $datapagameto = $item['releaseDate']; }else{ $datapagameto = date('Y-m-d'); }
          //valor pago
          if(!empty($item['amount'])){ $valorpago = $item['amount']; }else{ $valorpago = $json['amount']; }

          $status = 'RECEBIDO';
          mysqli_query($conexao,"UPDATE cobranca SET valorpago='$valorpago',datapagamento='$datapagameto',situacao='$status' WHERE idcobranca='$code' AND banco='Banco Juno'") or die (mysqli_error($conexao));
          //alimeNta o caixa
          mysqli_query($conexao,"INSERT INTO caixa (banco,tipo,nomecliente,descricao,valor,valorpago,pix,data,datapagamento,user) 
          VALUES ('Banco Juno','ENTRADA','$nomecliente','BOLETO','$valorpago','$valorpago','$valorpago','$datapagameto','$datapagameto','BAIXA SISTEMA')") 
          or die (mysqli_error($conexao));
          echo 'Recebido com sucesso';      
      }
    }
}

  
?>