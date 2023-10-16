<?php
ob_start();
@session_start();
include('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$nomeuser = $_SESSION['usuario'];
@$iduser = $_SESSION['iduser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

//token privado da empresa
$query2 = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));
$retPrivado = mysqli_fetch_array($query2);
$tokenprivado = $retPrivado['tokenprivado'];
$cliente_id = $retPrivado['clienteid'];
$cliente_secret = $retPrivado['clientesecret'];
//desenvolcido por André R. Melo
define('TOKENPRIVADO', $tokenprivado);
define('JUNO_CLIENT_ID', $cliente_id);
define('JUNO_CLIENT_SECRET', $cliente_secret);
//define('JUNO_OAUTH2', 'https://sandbox.boletobancario.com/authorization-server/oauth/token');
//define('URL', 'https://sandbox.boletobancario.com/api-integration/charges/');
define('JUNO_OAUTH2', 'https://api.juno.com.br/authorization-server/oauth/token');
define('URL','https://api.juno.com.br/charges/');

########################################################## OBTER TOKEN JUNO ######################################################################
function AccessToken()
{
    //gera o token
    $scope = "all";
    $post = array(
        'grant_type' => 'client_credentials',
        'scope' => $scope,
        "expires_in" => 3600
    );
    $base64 = base64_encode(JUNO_CLIENT_ID . ':' . JUNO_CLIENT_SECRET);
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
    include('conexao.php');
    $idempresa = $_SESSION['idempresa'];
    //verificar token no banco se ainda é válido
    $query = mysqli_query($conexao, "SELECT * FROM token_cli WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));
    if (mysqli_num_rows($query) == 1) {
        $ret = mysqli_fetch_array($query);
        $idtoken = $ret['id'];
        //comparar daa hora
        if ($ret['expira'] > date('Y-m-d H:i:s')) {
            $access_token = $ret['token'];
        } else {
            mysqli_query($conexao, "DELETE FROM token_cli WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));
            //exckuir e salvar novo
            $datahora = date('Y-m-d H:i:s');
            $datahora = date('Y-m-d H:i:s ', strtotime('+25 minutes', strtotime($datahora)));
            mysqli_query($conexao, "INSERT INTO token_cli (idempresa,token,data,expira) 
        VALUES ('$idempresa','$access_token',NOW(),'$datahora')") or die(mysqli_error($conexao));
            //print('Gerado e salvo com sucesso');         
        }
    } else {
        //e não existir token ainda adiciona um
        $datahora = date('Y-m-d H:i:s');
        $datahora = date('Y-m-d H:i:s ', strtotime('+25 minutes', strtotime($datahora)));
        mysqli_query($conexao, "INSERT INTO token_cli (idempresa,token,data,expira) 
      VALUES ('$idempresa','$access_token',NOW(),'$datahora')") or die(mysqli_error($conexao));
        //print('Gerado e salvo com sucesso'); 
    }

    return $access_token;
}

########################################################## GERAR COBRANÇA ######################################################################
function gerarCobranca($idcliente, $nparcelas, $vencimento, $valor, $obs)
{
    include('conexao.php');
    $obs = AspasBanco($obs);
    $vencimento = dataBanco($vencimento);
    //dados do cliente
    $queryCliente = mysqli_query($conexao, "SELECT * FROM user WHERE id='$idcliente'") or die(mysqli_error($conexao));
    $ddcliente = mysqli_fetch_array($queryCliente);
    @$valor = $valor; //VALOR BOLETO
    $idempresa = $ddcliente['idempresa'];
    @$doccliente = $ddcliente['cpf_cnpj'];

    //token privado da empresa
    $query2 = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='999'") or die(mysqli_error($conexao));
    
    if (mysqli_num_rows($query2) > 0) { //dados banco
    $diabloqueio = date('Y-m-d', strtotime('+'.$retPrivado['diasbloqueio'].' days', strtotime($vencimento)));
        $retPrivado = mysqli_fetch_array($query2);

        $aposvencimento = $retPrivado['aposvencimento'];
        if ($retPrivado['multaapos'] != '0.00') {
            $multaapos = $retPrivado['multaapos'];
        } else {
            $multaapos = "";
        }
        if ($retPrivado['jurosapos'] != '0.00') {
            $jurosapos = $retPrivado['jurosapos'];
        } else {
            $jurosapos = "";
        }
        if ($retPrivado['valordesconto'] != '0.00') {
            $valordesconto = $retPrivado['valordesconto'];
        } else {
            $valordesconto = "";
        }
        $diasparadesconto = $retPrivado['diasdesconto'];

        if (!empty($retPrivado['chavepixaleatoria'])) {
            $tipoboleto = 'BOLETO_PIX';
            @$tipocobranca = ['BOLETO_PIX']; //TIPO DE COBANCA: BOLETO OU BOLETO_PIX
            $chavepixaleatoria = $retPrivado['chavepixaleatoria'];
            $multaapos = "";
            $jurosapos = "";
            $valordesconto = "";
            $diasparadesconto = "";
        } else {
            $chavepixaleatoria = "";
            $tipoboleto = 'BOLETO';
            @$tipocobranca = ['BOLETO']; //TIPO DE COBANCA: BOLETO OU BOLETO_PIX
        }
        //token empresa
        $token = AccessToken();
        $curl = curl_init();
        $charge = (object)array(
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
            "dueDate" => $vencimento,
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
            "paymentTypes" => $tipocobranca,
            //válido apenas para cartão credito, adiantamento de valor
            "paymentAdvance" => false,
        );

        $address = (object)array(
            "street" => AspasBanco($ddcliente['rua']),
            "number" => "",
            "complement" => "",
            "neighborhood" => AspasBanco($ddcliente['bairro']),
            "city" => AspasBanco($ddcliente['cidade']),
            "state" => AspasBanco($ddcliente['estado']),
            "postCode" => limpar($ddcliente['cep'])
        );

        $billing = (object)array(
            "name" => $ddcliente['nome'],
            "document" => limpar($doccliente),
            "email" => $ddcliente['email'],
            "address" => $address,
            "notify" => true,
        );

        $dados = (object)array(
            "charge" => $charge,
            "billing" => $billing
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => URL,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dados),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'X-Api-Version: 2',
                'X-Resource-Token:' . TOKENPRIVADO,
                'Content-Type: application/json;charset=UTF-8'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true);
        //print_r($json);
        if (!empty($json['error'])) {
            $jsonerror = $json['details'];
            foreach ($jsonerror as $itemerror) {
                if (!empty(AspasBanco(@$itemerror['field']))) {
                    @$campo = AspasBanco(@$itemerror['field']);
                } else {
                    @$campo = '';
                };
                $message = AspasBanco($itemerror['message']);
                $log = AspasBanco('Campo: ' . $campo . ' | Erro: ' . $message);
                echo deletePersona(@$log);
            }
        } else {
            $json2 = $json['_embedded']['charges'];
            foreach ($json2 as $item) {
                //tratar retorno
                $idcobranca = $item['id'];
                $code = $item['code'];
                $vencimento = $item['dueDate'];
                //$link = $item['link'];
                $codigobarra = $item['payNumber'];
                $link = $item['installmentLink'];
                //idcliente	link codigobarra,ncobranca,cliente	vencimento	valor	datapagamento	situacao	
                mysqli_query($conexao, "INSERT INTO user_cobranca (idcliente,code,link,codigobarra,ncobranca,vencimento,diabloqueio,valor,situacao)
                                        VALUES ('$idcliente','$code','$link','$codigobarra','$idcobranca','$vencimento','$diabloqueio',$valor','PENDENTE')")
                    or die(mysqli_error($conexao));
                echo insert();
            }
        } //fim retorno cobrança gerada
    } else {
        echo deletePersona('Dados de banco incompleto');
    } //fim dados banco
}

########################################################## CONSULTA ######################################################################
function consultarCobranca($id)
{
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT ncobranca FROM user_cobranca WHERE id='$id'") or die(mysqli_error($conexao));
    $ddcc = mysqli_fetch_array($query);
    $code = $ddcc['ncobranca'];

    $token = AccessToken();
    if (!empty(@$token)) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => URL . $code,
            //CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'GET',
            //CURLOPT_POSTFIELDS => json_encode($dados),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'X-Api-Version: 2',
                'X-Resource-Token:' . TOKENPRIVADO,
                'Content-Type: application/json;charset=UTF-8'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true);
        //print_r($response);
        if ($json['status'] == 'PAID') {
            $json2 = $json['payments'];
            //datapagamento
            if (!empty($item['releaseDate'])) {
                $datapagameto = $item['releaseDate'];
            } else {
                $datapagameto = date('Y-m-d');
            }
            mysqli_query($conexao, "UPDATE user_cobranca SET datapagamento='$datapagameto',situacao='RECEBIDO' WHERE id='$id'") or die(mysqli_error($conexao));
            echo persona('Recebido com sucesso');
        } else {
            persona('Aguardando pagamento');
        }
    } else {
        echo deletePersona('Sem token');
    }
}

########################################################## RECEBER ######################################################################
function receberCobranca($id)
{
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT * FROM user_cobranca WHERE id='$id'") or die(mysqli_error($conexao));
    $ddcc = mysqli_fetch_array($query);
    $code = $ddcc['ncobranca'];

    $token = AccessToken();
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => URL . $code . '/cancelation',
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token,
            'X-Api-Version: 2',
            'X-Resource-Token: ' . TOKENPRIVADO,
            'Content-Type: application/json;charset=UTF-8'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response, true);
    // print_r($response);
    //se for me branco a resposta
    mysqli_query($conexao, "UPDATE user_cobranca SET situacao='RECEBIDO',datapagamento=NOW() WHERE id='$id'") or die(mysqli_error($conexao));

    echo persona('Recebido com sucesso');
}

########################################################## CONCELAR ######################################################################
function cancelarCobranca($id)
{
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT * FROM user_cobranca WHERE id='$id'") or die(mysqli_error($conexao));
    $ddcc = mysqli_fetch_array($query);
    $code = $ddcc['ncobranca'];

    $token = AccessToken();
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => URL . $code . '/cancelation',
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token,
            'X-Api-Version: 2',
            'X-Resource-Token: ' . TOKENPRIVADO,
            'Content-Type: application/json;charset=UTF-8'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($response, true);
    //print_r($response);
    //se for me branco a resposta
    mysqli_query($conexao, "DELETE FROM user_cobranca WHERE id='$id'") or die(mysqli_error($conexao));
    echo deletePersona('Cancelado');
}
