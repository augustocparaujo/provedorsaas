<?php
@$id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
if (!empty($id)) {
    include_once('conexao.php');
    include_once('funcoes.php');
    //do cascata
    $query = mysqli_query($conexao, "SELECT cobranca.*, 
    cliente.cpf_cnpj,nome,email,cep,rua,numero,bairro,municipio,estado,
    dadoscobranca.tokenprivado,url 
    FROM cobranca 
    INNER JOIN dadoscobranca ON dadoscobranca.idempresa = cobranca.idempresa
    LEFT JOIN cliente ON cliente.id = cobranca.idcliente
    WHERE cobranca.id='$id' AND cobranca.situacao='PENDENTE' AND dadoscobranca.recebercom='Mercadopago'") or die(mysqli_error($conexao));
    $ret = mysqli_fetch_array($query);
    $token = $ret['tokenprivado'];
    define('URL_NOTIFICACAO', $ret['url']);
    //dados cliente
    $n = explode(" ", $ret['nome']);
    $nome = $n['0'];
    $sobrenome = @$n['1'] . ' ' . @$n['2'];
    $cep = intval($ret['cep']);
    $rua = $ret['rua'];
    $numero = $ret['numero'];
    $bairro = $ret['bairro'];
    $municipio = $ret['municipio'];
    $estado = $ret['estado'];

    $valor = intval(AspasBanco($_POST['valor']));
    $vencimento = dataBanco($_POST['vencimento']) . 'T23:59:59.000-04:00';
    $descricao = AspasBanco($_POST['descricao']);
    $email = filter_var($ret['email'], FILTER_SANITIZE_EMAIL);
    $doc = filter_var($ret['cpf_cnpj'], FILTER_SANITIZE_NUMBER_INT);
    if (strlen($ret['cpf_cnpj']) == 11) {
        $tipodoc = 'cpf';
    } else {
        $tipodoc = 'cnpj';
    }
    //dados do cartao
    if ($_POST['formapagamento'] == 'PIX') {

        require_once 'vendor/autoload.php';
        MercadoPago\SDK::setAccessToken($token);
        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = $valor;
        $payment->description = $descricao;
        $payment->installments = 1;
        $payment->payment_method_id = "pix";
        $payment->notification_url = URL_NOTIFICACAO;
        $payment->payer = array(
            "email" => $email,
            "first_name" => $nome,
            "last_name" => $sobrenome,
            "identification" => array(
                "type" => $tipodoc,
                "number" => $doc
            )
        );
        $payment->save();
        //print_r($payment);
        if ($payment->status == 'pending') {
            $charge_id = $payment->id;
            $link = $payment->point_of_interaction->transaction_data->ticket_url;
            sleep(2);
            //receber se ok e dar update cobrança com dados do pix para baixa
            include_once('conexao.php');
            mysqli_query($conexao, "UPDATE cobranca SET idcobranca='$charge_id',link='$link',atualizado=NOW() WHERE id='$id'") or die(mysqli_error($conexao));
            echo 'sucesso';
        }
    } elseif ($_POST['formapagamento'] == 'Boleto') {
        require_once 'vendor/autoload.php';

        MercadoPago\SDK::setAccessToken($token);
        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = $valor;
        $payment->description = $descricao;
        $payment->date_of_expiration = $vencimento;
        $payment->payment_method_id = "bolbradesco";
        $payment->notification_url = URL_NOTIFICACAO;
        $payment->payer = array(
            "email" => $email,
            "first_name" => $nome,
            "last_name" => $sobrenome,
            "identification" => array(
                "type" => $tipodoc,
                "number" => $doc
            ),
            "address" =>  array(
                "zip_code" => $cep,
                "street_name" => $rua,
                "street_number" => $numero,
                "neighborhood" => $bairro,
                "city" => $municipio,
                "federal_unit" => $estado
            )
        );

        $payment->save();

        print_r($payment);
    } elseif ($_POST['formapagamento'] == 'Cartão') {
        $nomecartao = $_POST['nomecartao'];
        $numerocartaov = intval($_POST['numerocartao']);
        $validade = $_POST['validade'];
        $codigocvv = intval($_POST['codigocvv']);

        require_once 'vendor/autoload.php';
        MercadoPago\SDK::setAccessToken($token);
        $contents = json_decode(file_get_contents('php://input'), true);
        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = $valor;
        $payment->token = $token;
        $payment->installments = 1;
        $payment->payment_method_id = "credit_card";
        $payment->issuer_id = $contents['issuer_id'];
        $payer = new MercadoPagoPayer();
        $payer->email = $email;
        $payer->identification = array(
            "type" => $contents['payer']['identification']['type'],
            "number" => $contents['payer']['identification']['number']
        );
        $payment->payer = $payer;
        $payment->save();
        $response = array(
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id
        );
        echo json_encode($response);
    } else {
        echo 'algo deu errado';
    }
}
