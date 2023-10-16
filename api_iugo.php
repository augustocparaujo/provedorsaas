<?php
ob_start();
@session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

########################################################## OBTER TOKEN JUNO ######################################################################
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

  //verificar token no banco se ainda é válido
  $query = mysqli_query($conexao,"SELECT * FROM token_cli WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
  if(mysqli_num_rows($query) == 1){
    $ret = mysqli_fetch_array($query);
    $idtoken = $ret['id'];
    //comparar daa hora
      if($ret['expira'] > date('Y-m-d H:i:s')){
        $access_token = $ret['token'];
      }else{
        mysqli_query($conexao,"DELETE FROM token_cli WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
        //exckuir e salvar novo
        $datahora = date('Y-m-d H:i:s');
        $datahora = date('Y-m-d H:i:s ',strtotime('+25 minutes',strtotime($datahora)));
        mysqli_query($conexao,"INSERT INTO token_cli (idempresa,token,data,expira) 
        VALUES ('$idempresa','$access_token',NOW(),'$datahora')") or die (mysqli_error($conexao));
        //print('Gerado e salvo com sucesso');         
      } 
    }else{
      //e não existir token ainda adiciona um
      $datahora = date('Y-m-d H:i:s');
      $datahora = date('Y-m-d H:i:s ',strtotime('+25 minutes',strtotime($datahora)));
      mysqli_query($conexao,"INSERT INTO token_cli (idempresa,token,data,expira) 
      VALUES ('$idempresa','$access_token',NOW(),'$datahora')") or die (mysqli_error($conexao));
      //print('Gerado e salvo com sucesso'); 
    }  

    return $access_token;
} 

########################################################## GERAR COBRANÇA ######################################################################

function gerarCobranca($idcliente,$nparcelas,$vencimento,$valor,$obs){
  include('conexao.php');  
  $nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
  $obs = AspasBanco($obs);
      //dados do cliente
      $queryCliente = mysqli_query($conexao,"SELECT * FROM cliente WHERE id='$idcliente'") or die (mysqli_error($conexao));
      $ddcliente = mysqli_fetch_array($queryCliente);
      @$nomecliente = $ddcliente['nome']; //NOME CLIENTE
      @$valor = $valor; //VALOR BOLETO
      $idempresa = $ddcliente['idempresa'];
      if(@$ddcliente['cpf'] != '' AND @$ddcliente['cnpj'] == ''){ @$doccliente = $ddcliente['cpf']; } else{ @$doccliente = $ddcliente['cnpj']; }  //DOCUMENTO BOLETO
                  //token privado da empresa
                  $query2 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND recebercom='Banco Juno'") or die (mysqli_error($conexao));
                  if(mysqli_num_rows($query2) == 1){//dados banco
                    $retPrivado = mysqli_fetch_array($query2);
                    $tokenprivado = $retPrivado['tokenprivado'];
                    $aposvencimento = $retPrivado['aposvencimento'];
                    if($retPrivado['multaapos'] != '0.00'){ $multaapos = $retPrivado['multaapos']; }else{ $multaapos = "";}
                    if($retPrivado['jurosapos'] != '0.00'){ $jurosapos = $retPrivado['jurosapos']; }else{ $jurosapos = ""; }
                    if($retPrivado['valordesconto'] != '0.00'){ $valordesconto = $retPrivado['valordesconto']; }else{ $valordesconto = ""; }
                    $diasparadesconto = $retPrivado['diasdesconto'];

                      if(!empty($retPrivado['chavepixaleatoria'])){ 
                        $tipoboleto = 'BOLETO_PIX'; 
                        @$tipocobranca = ['BOLETO_PIX']; //TIPO DE COBANCA: BOLETO OU BOLETO_PIX
                        $chavepixaleatoria = $retPrivado['chavepixaleatoria']; 
                        $multaapos = "";
                        $jurosapos = ""; 
                        $valordesconto = "";
                        $diasparadesconto = "";
                      }else{ 
                        $chavepixaleatoria = ""; 
                        $tipoboleto = 'BOLETO';
                        @$tipocobranca = ['BOLETO']; //TIPO DE COBANCA: BOLETO OU BOLETO_PIX
                      }    
                                //token empresa
                                $token = AccessToken($idempresa);                                   
                                $curl = curl_init();
                                $charge=(object)array(
                                  //chave pix
                                  "pixKey" => $chavepixaleatoria,
                                  //obter qr code
                                  "pixIncludeImage" => true,
                                  //decrição da cobrança
                                  "description" => $obs,
                                  //total se for mais de um
                                  //"totalAmount" => 20.00,
                                  //valor da cobrança única ou por parcela
                                  "amount" => $valor,
                                  //vencimento
                                  "dueDate"=> dataBanco($vencimento),
                                  //numero total de parcelas
                                  "installments" => $nparcelas,
                                  //número de dias para pagamento apos o vencimento
                                  "maxOverdueDays" => $aposvencimento,
                                  //multa após o vencimento
                                  "fine" => $multaapos,
                                  //juros ao mês
                                  "interest" => $jurosapos,
                                  //valor do descont
                                  "discountAmount" => $valordesconto,
                                  //múmero de dias para desconto
                                  "discountDays" => $diasparadesconto,
                                  //tipo de cobrança
                                  "paymentTypes"=> $tipocobranca,
                                  //válido apenas para cartão credito, adiantamento de valor
                                  "paymentAdvance" => false,
                                );

                                $address=(object)array(
                                  "street" => AspasBanco($ddcliente['rua']),
                                  "number" => "",
                                  "complement" => "",
                                  "neighborhood" => AspasBanco($ddcliente['bairro']),
                                  "city" => AspasBanco($ddcliente['municipio']),
                                  "state" => AspasBanco($ddcliente['estado']),
                                  "postCode" => limpar($ddcliente['cep'])
                                );

                                $billing=(object)array(
                                  "name" => $ddcliente['nome'],
                                  "document"=> limpar($doccliente),
                                  "email"=> $ddcliente['email'],
                                  "address"=>$address,
                                  "notify" => true,
                                );

                                $dados = (object)array(
                                  "charge"=>$charge,
                                  "billing"=>$billing                              
                                );

                                //$url = 'https://sandbox.boletobancario.com/api-integration/charges/';
                                $url = 'https://api.juno.com.br/api-integration/charges/';                            
                                curl_setopt_array($curl, array(
                                CURLOPT_URL => $url,
                                CURLOPT_SSL_VERIFYPEER => true,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_MAXREDIRS => 2,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => json_encode($dados),
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
                              //print_r($json);
                              //print('<hr>');
                              if(!empty($json['error'])){ 
                                  $jsonerror = $json['details'];
                                  foreach ($jsonerror as $itemerror) {
                                    if(!empty(AspasBanco(@$itemerror['field']))){ @$campo = AspasBanco(@$itemerror['field']);} else{ @$campo = '';};
                                    $message = AspasBanco($itemerror['message']);
                                    $log = AspasBanco('Campo: '.$campo.' | Erro: '.$message);
                                    mysqli_query($conexao,"INSERT INTO log_cobranca (idcliente,cliente,data,log) 
                                    VALUE ('$idcliente','$nomecliente',NOW(),'$log')") or die (mysqli_error($conexao));
                                    
                                    echo deletePersona(@$log);
                                  } 
                              }else{
                                    $json2 = $json['_embedded']['charges'];
                                    foreach ($json2 as $item) { 
                                      //tratar retorno
                                      $idcobranca = $item['id'];
                                      $code = $item['code'];
                                      $vencimento = $item['dueDate'];
                                      $link = $item['link'];
                                      $codigobarra = $item['payNumber'];
                                      $installmentLink = $item['installmentLink'];
                                      $pdf = $item['link'];
                                      if($item['status'] == 'ACTIVE'){ $situacao = 'PENDENTE'; }else{ $situacao = $item['status']; }; 
                                        if($situacao == "PENDENTE"){                
                                          if(!empty($link) AND !empty($idcobranca)){
                                            mysqli_query($conexao,"INSERT INTO cobranca (idempresa,idcliente,idcobranca,nparcela,banco,tipo,tipocobranca,code,link,
                                            installmentLink,codigobarra,cliente,descricao,obs,vencimento,valor,situacao,datagerado)
                                            VALUES ('$idempresa','$idcliente','$idcobranca','$nparcelas','Banco Juno','$tipoboleto','plano','$code','$installmentLink','$link',
                                            '$codigobarra','$nomecliente','$obs','$obs','$vencimento','$valor','$situacao',NOW())") 
                                            or die (mysqli_error($conexao));

                                            //dispara email: $vencimento,$valor,$codigobarra,$link,$pdf,$emailcliente,$nomecliente
                                            /*
                                            include_once('api_email.php');
                                            $nomecliente = $ddcliente['nome'];
                                            $emailcliente = $ddcliente['email'];
                                            enviaEmail($vencimento,$valor,$codigobarra,$link,$pdf,$emailcliente,$nomecliente);
                                            */

                                          }
                                          echo insert();                                          
                                        }
                                    }
                              }//fim retorno cobrança gerada
                  }else{
                    echo deletePersona('Dados de banco incompleto');  
                  }//fim dados banco
}

########################################################## CONSULTA SE JÁ FOI PAGO ######################################################################

function consultarCobranca($id){
  $nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
  include('conexao.php'); 
  $query = mysqli_query($conexao,"SELECT cobranca.*, cliente.nome FROM cobranca 
  LEFT JOIN cliente ON cobranca.idcliente = cliente.id
  WHERE cobranca.id='$id' AND cobranca.banco='Banco Juno'") or die (mysqli_error($conexao));
  $ddcc = mysqli_fetch_array($query);
  $code = $ddcc['idcobranca'];
  $nomecliente = $ddcc['nome'];
  $idempresa = $ddcc['idempresa'];
  $idcliente = $ddcc['idempresa'];
  
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
        //print_r($response);
      if($json['status'] == 'PAID' OR $json['status'] == 'MANUAL_RECONCILIATION'){ 
              //resposta do pagamento
              $json2 = $json['payments'];
              //percore o array para ver a data dop pagamento
              //foreach ($json2 as $item) { 
                //datapagamento
                if(!empty($item['releaseDate'])){ $datapagameto = $item['releaseDate']; }else{ $datapagameto = date('Y-m-d'); }
                //valor pago
                if(!empty($item['amount'])){ $valorpago = $item['amount']; }else{ $valorpago = Moeda($json['amount']); }

          $status = 'RECEBIDO';
          mysqli_query($conexao,"UPDATE cobranca SET valorpago='$valorpago',datapagamento='$datapagameto',situacao='$status' WHERE idcobranca='$code' AND banco='Banco Juno'") or die (mysqli_error($conexao));
          //alimeNta o caixa
          mysqli_query($conexao,"INSERT INTO caixa (banco,tipo,nomecliente,descricao,valor,valorpago,pix,data,datapagamento,user) 
          VALUES ('Banco Juno','ENTRADA','$nomecliente','BOLETO','$valorpago','$valorpago','$valorpago','$datapagameto','$datapagameto','BAIXA SISTEMA')") 
          or die (mysqli_error($conexao));

          $sqlV = mysqli_query($conexao,"SELECT login,plano FROM contratos WHERE idcliente='$idcliente'") or die (mysqli_error($conexao));
          if(mysqli_num_rows($sqlV) >= 1){
              while($ddc = mysqli_fetch_array($sqlV)){
                  $login = $ddc['login'];
                  $plano = $ddc['plano'];                
 
                  //servidor
                  $sql2 = mysqli_query($conexao,"SELECT plano.*, servidor.ip,user,password FROM plano 
                  LEFT JOIN servidor ON plano.servidor = servidor.id
                  WHERE plano.id='$plano'");
                  $dds = mysqli_fetch_array($sql2);
                  $planonome = $dds['plano'];
                              
                  require_once('routeros_api.class.php');
                  $mk = new RouterosAPI();
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
              }
          }

          echo persona('Recebido com sucesso');

        }else{ persona('Aguardando pagamento'); }
    }else{ echo deletePersona('Sem token'); }
}

########################################################## CONCELAR COBRANÇA ######################################################################

function cancelarCobranca($id){
  $nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
  include('conexao.php');
  $query = mysqli_query($conexao,"SELECT cobranca.*, cliente.nome FROM cobranca 
  LEFT JOIN cliente ON cobranca.idcliente = cliente.id
  WHERE cobranca.id='$id' AND cobranca.banco='Banco Juno'") or die (mysqli_error($conexao));
  $ddcc = mysqli_fetch_array($query);
  $code = $ddcc['idcobranca'];
  $idempresa = $ddcc['idempresa'];
  
   $query2 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE recebercom='Banco Juno' AND idempresa='$idempresa'") or die (mysqli_error($conexao));
    $retPrivado = mysqli_fetch_array($query2);
    $tokenprivado = $retPrivado['tokenprivado'];

  $token = AccessToken($idempresa);
  //$url = 'https://sandbox.boletobancario.com/api-integration/charges/';
  $url = 'https://api.juno.com.br/charges/'; 
      $curl = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => $url.$code.'/cancelation',
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_MAXREDIRS => 2,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token,
        'X-Api-Version: 2',
        'X-Resource-Token: '.$tokenprivado,
        'Content-Type: application/json;charset=UTF-8'
          ),
      ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response,true);
    // print_r($response);
    //se for me branco a resposta
    mysqli_query($conexao,"UPDATE cobranca SET situacao='CANCELADO',usuarioatualizou='$nomeuser',atualizado=NOW() WHERE idcobranca='$code' AND banco='Banco Juno'") or die (mysqli_error($conexao));  
    mysqli_query($conexao,"UPDATE caixa SET tipo='CANCELADO' WHERE titulo='$code'") or die(mysqli_error($conexao)); 
    echo deletePersona('Cancelado');
}

//receber --> converter pra carteira e cancelar juno
function receberCobranca($id,$valorpago,$datapagamento){
  include('conexao.php');
  $nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
  $query = mysqli_query($conexao,"SELECT cobranca.*, cliente.nome FROM cobranca 
  LEFT JOIN cliente ON cobranca.idcliente = cliente.id
  WHERE cobranca.id='$id' AND cobranca.banco='Banco Juno'") or die (mysqli_error($conexao));
  $ddcc = mysqli_fetch_array($query);
  $code = $ddcc['idcobranca'];
  $idempresa = $ddcc['idempresa'];
  $nomecliente = $ddcc['nome'];
  $descicao = $r['obs'].'(Recebido em carteira)';
  
   $query2 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE recebercom='Banco Juno' AND idempresa='$idempresa'") or die (mysqli_error($conexao));
    $retPrivado = mysqli_fetch_array($query2);
    $tokenprivado = $retPrivado['tokenprivado'];

  $token = AccessToken($idempresa);
  //$url = 'https://sandbox.boletobancario.com/api-integration/charges/';
  $url = 'https://api.juno.com.br/charges/'; 
      $curl = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => $url.$code.'/cancelation',
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_MAXREDIRS => 2,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token,
        'X-Api-Version: 2',
        'X-Resource-Token: '.$tokenprivado,
        'Content-Type: application/json;charset=UTF-8'
          ),
      ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response,true);
    // print_r($response);
    //se for me branco a resposta
    mysqli_query($conexao,"UPDATE cobranca SET situacao='RECEBIDO',valorpago='$valorpago',datapagamento='$datapagamento',obs='$descicao',usuarioatualizou='$nomeuser',atualizado=NOW() WHERE idcobranca='$code' AND banco='Banco Juno'") or die (mysqli_error($conexao));  

    echo persona('Recebido com sucesso');
}

?>