<?php
@session_start();
include('conexao.php'); 
include('funcoes.php');
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

//token privado da empresa
$query2 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE idempresa='999' AND recebercom='Gerencianet'") or die (mysqli_error($conexao));
$retPrivado = mysqli_fetch_array($query2);
//sandbox
//define('URL_API','https://sandbox.gerencianet.com.br');
//produção
define("URL_API","https://api.gerencianet.com.br");
define("aposvencimento", $retPrivado['aposvencimento']);
define("clienteid",$retPrivado['clienteid']);
define("secretid",$retPrivado['clientesecret']);
define("urlnotificacao", $retPrivado['url']);

function AcessoToken(){
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
//echo AcessoToken();


################################################# GERA BOLETO GNET ################################################
function gerarCobranca($idcliente,$nparcelas,$vencimento,$valor,$obs){
  $valorreal = $valor;
  $valorg = Intval(limpaCPF_CNPJ($valor)); //valor do boleto
  $obs = AspasBanco($obs); //descrição do boleto  
  $vencimento = dataBanco($vencimento);
  $nparcelas = Intval($nparcelas);
  $token = AcessoToken();

  for($i = 1; $i <= $nparcelas; $i++){

    ############################################################################
    $v1 = explode("-",$vencimento);
    $diaReal = $v1[2];

    if($i >= 2){           

        $v = explode("-",$dataVencimentoG);
        $mes = $v[1] + 1;

        if($mes == 02){
            $dataVencimentoG = date('Y-m-28',strtotime($dataVencimentoG));
        }else{
            $dataVencimentoG = date('Y-m-'.$diaReal,strtotime('+1 months',strtotime($dataVencimentoG)));
        }

    } else {              
        $dataVencimentoG = date('Y-m-d', strtotime($vencimento)); 
    }
    ############################################################################
    
      include('conexao.php');
      //dados do cliente
      $queryCliente = mysqli_query($conexao,"SELECT * FROM user WHERE id='$idcliente'") or die (mysqli_error($conexao));
      $ddcliente = mysqli_fetch_array($queryCliente);
      $nomecliente = AspasBanco($ddcliente['nome']); //nome cliente
      $email = $ddcliente['email']; //email cliente
      @$cpf_cnpj= $ddcliente['cpf_cnpj'];
      $contato = $ddcliente['contato'];
      $rua = $ddcliente['rua'];
      $numero = "";
      $bairro = $ddcliente['bairro'];
      $cep = $ddcliente['cep'];
      $municipio = $ddcliente['cidade'];
      $complemento = "";
      $estado = $ddcliente['estado'];

      $custom_id = gerarToken(false).gerarToken(true);

      $address = [
        "street" => $rua,
        "number" => $numero,
        "neighborhood" => $bairro,
        "zipcode" => $cep,
        "city" => $municipio,
        "complement" => $complemento,
        "state" => $estado,
    ];  

      if(strlen($cpf_cnpj) == 11){

        $customer = [
          "name" => $nomecliente, // nome do cliente
          "cpf" => $cpf_cnpj,
          "phone_number" => $contato, // telefone do cliente
          "address" => $address,
        ];

      }else{

        $juridical_person = [
          "corporate_name" => $nomecliente,
          "cnpj" => $cpf_cnpj,
        ];

        $customer = [
          "name" => $nomecliente, // nome do cliente
          "phone_number" => $contato, // telefone do cliente
          "address" => $address,
          "juridical_person" => $juridical_person,
        ];

      }


    
    //tratar e enviar dados
    $url = URL_API.'/v1/charge/one-step';
    $curl = curl_init();
    $item_1 = [
        "name" => $obs, // nome do item, produto ou serviço
        "amount" => 1, // quantidade
        "value" => $valorg, // valor (1000 = R$ 10,00) (Obs: É possível a criação de itens com valores negativos. Porém, o valor total da fatura deve ser superior ao valor mínimo para geração de transações.)
    ];
    $items = [
        $item_1
    ];
    $metadata = array(
      "custom_id" => $custom_id,
      "notification_url" => urlnotificacao."?customid=$custom_id"
      );  
    
    $customer;

    $bankingBillet = [
        "expire_at" => $dataVencimentoG, // data de vencimento do titulo
        "message" => $obs, // mensagem a ser exibida no boleto
        "customer" => $customer
    ];
    $payment = [
        "banking_billet" => $bankingBillet, // forma de pagamento (banking_billet = boleto)
    ];
    $body = [
        "items" => $items,
        "metadata" =>$metadata,
        "payment" => $payment,
    ];

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($body),
      CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer $token",
          "Content-Type: application/json"
        ),
    ));    
    $response = curl_exec($curl);    
    curl_close($curl);
    $json = json_decode($response,true);    
    //print_r($body);
    if($json['code'] == 200){ 

        $vencimentoreal = dataBanco($dataVencimentoG);
        $barcode = $json['data']['barcode'];
        $qrcode = $json['data']['pix']['qrcode'];
        $link = $json['data']['link'];
        $billet_link = $json['data']['billet_link'];
        $pdf = $json['data']['pdf']['charge'];
        $charge_id = $json['data']['charge_id'];
        $pixqrcode = $json['data']['pix']['qrcode'];

        //idcliente,code,link,codigobarra,ncobranca,obs,vencimento,valor,situacao,data	
        mysqli_query($conexao,"INSERT INTO user_cobranca (idcliente,code,link,codigobarra,ncobranca,obs,vencimento,valor,situacao,data)
        VALUES ('$idcliente','$charge_id','$link','$barcode','$custom_id','$obs','$vencimentoreal','$valor','PENDENTE',NOW())");

        echo insert();        
    
    }else{ 
      echo persona('Gerencianet informa: '.$json['code'].', <br />erro: '.$json['error'].'<br /> Descricação: '.$json['error_description'].'<br />'.@$json['error_description']['property'].'<br />'.@$json['error_description']['message']);
      //print_r($response);
    }

    }
}
//echo gerarCobrancaGerencianet();

################################################# CONSULTA BOLETO GNET ################################################
//consultar -> /v1/charge/:id
function consultarCobranca($code){ 
  $token = AcessoToken();
  $url = URL_API.'/v1/charge/';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url.$code,
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
  if($json['code'] == 200 AND $json['data']['status'] == 'paid'){ //se sucesso na geração

    include('conexao.php');
    mysqli_query($conexao,"UPDATE user_cobranca SET situacao='RECEBIDO' WHERE code='$code'") or die (mysqli_error($conexao));
    
    //desbloquear
    $sql = mysqli_query($conexao,"SELECT idcliente FROM user_cobranca WHERE code='$code'");
    $ret = mysqli_fetch_array($sql);
    mysqli_query($conexao,"UPDATE user SET situacao='1' WHERE id='$ret[idcliente]'");

    echo persona('RECEBIDO');

  }else{
    persona('Aguardando pagamento'); 
  }
}
//echo ConsultaBoletoGerenciaNet(1659661);

################################################# CANCELA BOLETO GNET ################################################

//cancelar boleto -> /v1/charge/:id/cancel
function cancelarCobranca($code){
  $token = AcessoToken();
  $url = URL_API.'/v1/charge/';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url.$code.'/cancel',
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 2,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer $token",
      "Content-Type: application/json"
        ),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  $json = json_decode($response,true);
  //print_r($response);
  if($json['code'] == 200){ //se sucesso no cancelamento
    include('conexao.php');
      ///nome user não está vindo com o log
    mysqli_query($conexao,"DELETE FROM user_cobranca WHERE code='$code'") or die (mysqli_error($conexao));
    echo deletePersona('Cancelado com sucesso!');

  }
}
//echo CancelarBoletoGerenciaNet(446694324);
