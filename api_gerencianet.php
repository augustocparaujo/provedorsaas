<?php
@session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

//token privado da empresa
$query2 = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND recebercom='Gerencianet'") or die(mysqli_error($conexao));
$retPrivado = mysqli_fetch_array($query2);
$aposvencimento = $retPrivado['aposvencimento'];
$multaapos = $retPrivado['multaapos'];
$jurosapos = $retPrivado['jurosapos'];
define("clienteid", $retPrivado['clienteid']);
define("secretid", $retPrivado['clientesecret']);
//sandbox
define('URL_API', 'https://sandbox.gerencianet.com.br');
//produção
// define("URL_API", "https://api.gerencianet.com.br");

function AcessoToken()
{
  $url = URL_API . '/v1/authorize';
  $base64 = base64_encode(clienteid . ':' . secretid);
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
    CURLOPT_POSTFIELDS => '{"grant_type": "client_credentials"}',
    CURLOPT_HTTPHEADER => array(
      'Authorization: Basic ' . $base64,
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
function gerarCobranca($idcliente, $nparcelas, $vencimento, $valor, $obs, $idempresa)
{
  include('conexao.php');
  $valorreal = $valor;
  $obs = $obs; //descrição do boleto  
  $vencimento = dataBanco($vencimento);
  $nparcelas = Intval($nparcelas);
  $token = AcessoToken();

  for ($i = 1; $i <= $nparcelas; $i++) {

    ############################################################################
    $v1 = explode("-", $vencimento);
    $diaReal = $v1[2];

    if ($i >= 2) {

      $v = explode("-", $dataVencimentoG);
      $mes = $v[1] + 1;

      if ($mes == 02) {
        $dataVencimentoG = date('Y-m-28', strtotime($dataVencimentoG));
      } else {
        $dataVencimentoG = date('Y-m-' . $diaReal, strtotime('+1 months', strtotime($dataVencimentoG)));
      }
    } else {
      $dataVencimentoG = date('Y-m-d', strtotime($vencimento));
    }
    ############################################################################

    //se segundo loop adiciona mais um mês
    ///if($i >= 2){ $vencimento = date('Y-m-d', strtotime('+1 month', strtotime(dataBanco($vencimento)))); /*1 mês*/ }else{ $vencimento = dataBanco($vencimento); }

    //dados da empresa
    $query2 = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND recebercom='Gerencianet'") or die(mysqli_error($conexao));
    if (mysqli_num_rows($query2) == 1) { //dados banco
      $retPrivado = mysqli_fetch_array($query2);
      $aposvencimento = $retPrivado['aposvencimento'];
      if ($retPrivado['multaapos'] != '0.00') {
        $multaapos = $retPrivado['multaapos'];
      } else {
        $multaapos = 0;
      }
      if ($retPrivado['jurosapos'] != '0.00') {
        $jurosapos = limpar($retPrivado['jurosapos']);
      } else {
        $jurosapos = 0;
      }
      if ($retPrivado['valordesconto'] != '0.00') {
        $valordesconto = Intval(limpaCPF_CNPJ($retPrivado['valordesconto']));
      } else {
        $valordesconto = 0;
      }
      $datadesconto = date('Y-m-d', strtotime('-' . $retPrivado['diasdesconto'] . ' days', strtotime($dataVencimentoG)));
      $urlnotificacao = $retPrivado['url'];

      //dados do cliente
      $queryCliente = mysqli_query($conexao, "SELECT * FROM cliente WHERE id='$idcliente'") or die(mysqli_error($conexao));
      $ddcliente = mysqli_fetch_array($queryCliente);
      $nomecliente = AspasBanco($ddcliente['nome']); //nome cliente
      $email = $ddcliente['email']; //email cliente
      if (@$ddcliente['cpf'] != '') {
        @$cpf = $ddcliente['cpf'];
      } else {
        @$cnpj = $ddcliente['cnpj'];
      }  //documento cliente
      $contato = $ddcliente['contato'];
      $rua = $ddcliente['rua'];
      $numero = $ddcliente['numero'];
      $bairro = $ddcliente['bairro'];
      $cep = $ddcliente['cep'];
      $municipio = $ddcliente['municipio'];
      $complemento = $ddcliente['complemento'];
      $estado = $ddcliente['estado'];
      $tipoboleto = $ddcliente['tipodecobranca'];

      $custom_id = gerarToken(false) . gerarToken(true);
      //$valor = $valor / $nparcelas;
      $valor = Intval(limpaCPF_CNPJ($valor)); //valor do boleto

      $address = [
        "street" => $rua,
        "number" => $numero,
        "neighborhood" => $bairro,
        "zipcode" => $cep,
        "city" => $municipio,
        "complement" => $complemento,
        "state" => $estado,
      ];

      if ($cpf != '') {

        $customer = [
          "name" => $nomecliente, // nome do cliente
          "cpf" => $cpf,
          "phone_number" => $contato, // telefone do cliente
          "address" => $address,
        ];
      } else {

        $juridical_person = [
          "corporate_name" => $nomecliente,
          "cnpj" => $cnpj,
        ];

        $customer = [
          "name" => $nomecliente, // nome do cliente
          "phone_number" => $contato, // telefone do cliente
          "address" => $address,
          "juridical_person" => $juridical_person,
        ];
      }



      //tratar e enviar dados
      $url = URL_API . '/v1/charge/one-step';
      $curl = curl_init();
      $item_1 = [
        "name" => $obs, // nome do item, produto ou serviço
        "amount" => 1, // quantidade
        "value" => $valor, // valor (1000 = R$ 10,00) (Obs: É possível a criação de itens com valores negativos. Porém, o valor total da fatura deve ser superior ao valor mínimo para geração de transações.)
      ];
      $items = [
        $item_1
      ];
      $metadata = array(
        "custom_id" => $custom_id,
        "notification_url" => "https://augustocezar.com.br/notificacoes.php?customid=$custom_id"
      );

      $customer;

      $configurations = [ // configurações de juros e mora        
        "fine" => $multaapos, // porcentagem de multa
        //"interest" => $jurosapos, // porcentagem de juros
      ];
      $conditional_discount = [ // configurações de desconto condicional
        "type" => "currency", // seleção do tipo de desconto 
        "value" => $valordesconto, // porcentagem de desconto
        "until_date" => $datadesconto, // data máxima para aplicação do desconto
      ];
      $bankingBillet = [
        "expire_at" => $dataVencimentoG, // data de vencimento do titulo
        "message" => $obs, // mensagem a ser exibida no boleto
        "customer" => $customer,
        "conditional_discount" => $conditional_discount,
      ];
      $payment = [
        "banking_billet" => $bankingBillet, // forma de pagamento (banking_billet = boleto)
      ];
      $body = [
        "items" => $items,
        "metadata" => $metadata,
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
      $json = json_decode($response, true);
      //print_r($json);
      if ($json['code'] == 200) {
        //status
        switch ($json['data']['status']) {
          case 'waiting':
            $situacao = 'PENDENTE';
            break;
          case 'unpaid':
            $situacao = 'PENDENTE';
            break;
          case 'paid':
            $situacao = 'RECEIBIDO';
            break;
          case 'settled':
            $situacao = 'RECEBIDO';
            break;
          case 'canceled':
            $situacao = 'CANCELADO';
            break;
        }

        $vencimentoreal = dataBanco($dataVencimentoG);
        $barcode = $json['data']['barcode'];
        $qrcode_image = AspasBanco($json['data']['pix']['qrcode_image']);
        $qrcode = AspasBanco($json['data']['pix']['qrcode']);
        $link = AspasBanco($json['data']['link']);
        $billet_link = AspasBanco($json['data']['billet_link']);
        $pdf = AspasBanco($json['data']['pdf']['charge']);
        $charge_id = $json['data']['charge_id'];

        $parcela = $i;
        mysqli_query($conexao, "INSERT INTO cobranca (idempresa,idcliente,custom_id,idcobranca,nparcela,parcela,banco,tipo,tipocobranca,code,link,
        installmentLink,pdf,codigobarra,ncobranca,cliente,descricao,obs,vencimento,valor,situacao,datagerado,qrcode,qrcode2)
        VALUES ('$idempresa','$idcliente','$custom_id','$charge_id','$nparcelas','$parcela','Gerencianet','$tipoboleto','plano','$charge_id','$link','$billet_link','$pdf',
        '$barcode','$charge_id','$nomecliente','$obs','$obs','$vencimentoreal','$valorreal','$situacao',NOW(),'$qrcode_image','$qrcode')")
          or die(mysqli_error($conexao));
        echo insert();
      } else {
        echo persona('Gerencianet informa: ' . $json['code'] . ', <br />erro: ' . $json['error'] . '<br /> Descricação: ' . $json['error_description'] . '<br />' . @$json['error_description']['property'] . '<br />' . @$json['error_description']['message']);
        //print_r($response);
      }
    } else {
      echo persona($json['error']);
    }
  }
}
//echo gerarCobrancaGerencianet();

################################################# CONSULTA BOLETO GNET ################################################
//consultar -> /v1/charge/:id
function consultarCobranca($id)
{
  include('conexao.php');
  $sql = mysqli_query($conexao, "SELECT * FROM cobranca WHERE id='$id' AND banco='Gerencianet'") or die(mysqli_error($conexao));
  $r = mysqli_fetch_array($sql);
  $idcobranca = $r['ncobranca'];
  $nomecliente = $r['cliente'];
  $idcliente = $r['idcliente'];

  $token = AcessoToken();
  $url = URL_API . '/v1/charge/';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url . $idcobranca,
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
  $json = json_decode($response, true);
  //print_r($response);
  //baixa no banco caso já tenha recebido  
  if ($json['code'] == 200) { //se sucesso na geração
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
        $valorcorrigido = $json['data']['total'] / 100;
        $valorpago = number_format((float)$valorcorrigido, 2, '.', '');
        break;
      case 'settled':
        $situacao = 'RECEBIDO';
        $valorcorrigido = $json['data']['total'] / 100;
        $valorpago = number_format((float)$valorcorrigido, 2, '.', '');
        break;
      case 'canceled':
        $situacao = 'CANCELADO';
        $valorpago = 0.00;
        break;
    }

    mysqli_query($conexao, "UPDATE cobranca SET valorpago='$valorpago',situacao='$situacao' WHERE id='$id' AND banco='Gerencianet'") or die(mysqli_error($conexao));
    echo persona($situacao);
    if ($situacao == 'RECEBIDO') {
      mysqli_query($conexao, "INSERT INTO caixa (banco,tipo,nomecliente,descricao,valor,valorpago,pix,data,datapagamento,user) 
    VALUES ('Gerencianet','ENTRADA','$nomecliente','BOLETO','$valorpago','$valorpago','$valorpago',NOW(),NOW(),'BAIXA SISTEMA')")
        or die(mysqli_error($conexao));

      #########################################################################################################
      $sqlV = mysqli_query($conexao, "SELECT login,plano FROM contratos WHERE idcliente='$idcliente'") or die(mysqli_error($conexao));
      if (mysqli_num_rows($sqlV) >= 1) {
        while ($ddc = mysqli_fetch_array($sqlV)) {
          $login = $ddc['login'];
          $plano = $ddc['plano'];

          //servidor
          $sql2 = mysqli_query($conexao, "SELECT plano.*, servidor.ip,user,password FROM plano 
             LEFT JOIN servidor ON plano.servidor = servidor.id
             WHERE plano.id='$plano'");
          $dds = mysqli_fetch_array($sql2);
          $planonome = $dds['plano'];

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
      #########################################################################################################

    }
  } elseif ($json['code'] != '') {
    echo persona('Erro código: ' . $json['code'] . ', <br />erro: ' . $json['error'] . '<br /> Descricação: ' . $json['error_description'] . '<br />' . @$json['error_description']['property'] . '<br />' . @$json['error_description']['message']);
  } else {
    persona('Aguardando pagamento');
  }
}
//echo ConsultaBoletoGerenciaNet(1659661);

################################################# CANCELA BOLETO GNET ################################################

//cancelar boleto -> /v1/charge/:id/cancel
function cancelarCobranca($id)
{
  include('conexao.php');
  $sql = mysqli_query($conexao, "SELECT * FROM cobranca WHERE id='$id' AND banco='Gerencianet'") or die(mysqli_error($conexao));
  $r = mysqli_fetch_array($sql);
  $idcobranca = $r['ncobranca'];

  $token = AcessoToken();
  $url = URL_API . '/v1/charge/';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url . $idcobranca . '/cancel',
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
  $json = json_decode($response, true);
  //print_r($response);
  if ($json['code'] == 200) { //se sucesso no cancelamento

    ///nome user não está vindo com o log
    mysqli_query($conexao, "UPDATE cobranca SET situacao='CANCELADO',usuarioatualizou='$nomeuser',atualizado=NOW() WHERE id='$id' AND banco='Gerencianet'") or die(mysqli_error($conexao));
    mysqli_query($conexao, "UPDATE caixa SET tipo='CANCELADO' WHERE titulo='$idcobranca'") or die(mysqli_error($conexao));
    echo deletePersona('Cancelado com sucesso!');
  } elseif ($json['code'] == 3500010) {
    echo deletePersona('Cobrança inexistente: ' . $idcobranca . '<br />Por favor verificar cobrança no banco');
  } else {
    if ($json['code'] == 3500037) {
      echo deletePersona('Vencimento inválido');
    } else {
      echo persona('Erro código: ' . $json['code'] . ', <br />erro: ' . $json['error'] . '<br /> Descricação: ' . $json['error_description'] . '<br />' . @$json['error_description']['property'] . '<br />' . @$json['error_description']['message']);
    }
  }
}
//echo CancelarBoletoGerenciaNet(446694324);


//cancelar boleto -> /v1/charge/:id/settle
function receberCobranca($id)
{
  include('conexao.php');
  $sql = mysqli_query($conexao, "SELECT * FROM cobranca WHERE id='$id' AND banco='Gerencianet'") or die(mysqli_error($conexao));
  $r = mysqli_fetch_array($sql);
  $idcobranca = $r['ncobranca'];
  $idcliente = $r['idcliente'];
  $valor = $r['valor'];
  $nomecliente = $r['cliente'];
  $descicao = $r['obs'] . '(Recebido em carteira)';

  $token = AcessoToken();
  $url = URL_API . '/v1/charge/';
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url . $idcobranca . '/settle',
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
  $json = json_decode($response, true);
  //print_r($response);
  if ($json['code'] == 200) { //se sucesso no cancelamento

    //se for me branco a resposta
    mysqli_query($conexao, "UPDATE cobranca SET situacao='RECEBIDO',valorpago='$valorpago',datapagamento='$datapagamento',obs='$descicao',usuarioatualizou='$nomeuser',atualizado=NOW() WHERE idcobranca='$code' AND banco='Banco Juno'") or die(mysqli_error($conexao));

    //-habilitar cliente

    function AspasBanco2($string)
    {
      $string = str_replace(chr(146) . chr(146), '"', $string);
      $string = str_replace(chr(146), "'", $string);
      return addslashes($string);
    };

    $query = mysqli_query($conexao, "SELECT * FROM contratos WHERE idcliente='$idcliente'") or die(mysqli_error($conexao));
    while ($ret = mysqli_fetch_array($query)) {
      if ($ret['situacao'] == 'Bloqueado') {
        $login = AspasBanco2($ret['login']);
        //plano cliente
        $plano = $ret['plano'];
        $queryplano = mysqli_query($conexao, "SELECT plano.plano,servidor,servidor.id,ip,user,password 
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
        if ($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
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
        mysqli_query($conexao, "UPDATE contratos SET situacao='ATIVO' WHERE idcliente='$idcliente'") or die(mysqli_error($conexao));
      }
    }

    mysqli_query($conexao, "UPDATE cliente SET situacao='ATIVO', atualizado=NOW() WHERE id='$idcliente'") or die(mysqli_error($conexao));

    echo persona('Recebido com sucesso!');
  } else {
    echo persona('Erro código: ' . $json['code'] . ', <br />erro: ' . $json['error'] . '<br /> Descricação: ' . $json['error_description']);
  }
}
//echo receberBoletoGerenciaNet(446694324);
