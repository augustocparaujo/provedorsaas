<?php
@session_start();
include_once('conexao.php');
include_once('funcoes.php');
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

$query = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE recebercom='Mercadopago'") or die(mysqli_error($conexao));
$ret = mysqli_fetch_array($query);
define("TOKEN", $ret['tokenprivado']);
define("URL_NOTIFICACAO", $ret['url']);

function gerarPix($id, $valor, $descricao, $email, $nome, $sobrenome, $tipodoc, $doc)
{
    require_once 'vendor/autoload.php';
    MercadoPago\SDK::setAccessToken(TOKEN);
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
        mysqli_query($conexao, "UPDATE cobranca SET idcobranca='$charge_id',link='$link',data=NOW() WHERE id='$id'") or die(mysqli_error($conexao));
    }
}


function gerarCartao($id, $nomecartao = $_POST['nomecartao']'';
$numerocartaov = '';
$validdemes = '';
$validadeano = '';
$codigocvv = '';)
{
    require_once 'vendor/autoload.php';
    MercadoPago\SDK::setAccessToken(TOKEN);
    $contents = json_decode(file_get_contents('php://input'), true);

    $payment = new MercadoPagoPayment();
    $payment->transaction_amount = $valor;
    $payment->token = TOKEN;
    $payment->installments = 1;
    $payment->payment_method_id = credit_card;
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
}

function gerarBoleto()
{
    require_once 'vendor/autoload.php';

    MercadoPago\SDK::setAccessToken(TOKEN);

    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = $valor;
    $payment->description = $descricao;
    $payment->payment_method_id = "bolbradesco";
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
}



/* -------------------------------- 

account_money: Money in the Mercado Pago account.
ticket: Boletos, Caixa Electronica Payment, PayCash, Efecty, Oxxo, etc.
bank_transfer: Pix and PSE (Pagos Seguros en Línea).
atm: ATM payment (widely used in Mexico through BBVA Bancomer).
credit_card: Payment by credit card.
debit_card: Payment by debit card.
prepaid_card: Payment by prepaid card.
digital_currency: Purchases with Mercado Crédito.
digital_wallet: Paypal.
voucher_card: Alelo benefits, Sodexo.
crypto_transfer: Payment with cryptocurrencies such as Ethereum and Bitcoin.
*/
