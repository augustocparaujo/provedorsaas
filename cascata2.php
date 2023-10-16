<?php

function gerarCartao()
{
  require_once 'vendor/autoload.php';
  MercadoPago\SDK::setAccessToken("YOUR_ACCESS_TOKEN");
  $contents = json_decode(file_get_contents('php://input'), true);

  $payment = new MercadoPago\Payment();
  $payment->transaction_amount = $contents['transaction_amount'];
  $payment->token = $contents['token'];
  $payment->installments = $contents['installments'];
  $payment->payment_method_id = $contents['payment_method_id'];
  $payment->issuer_id = $contents['issuer_id'];
  $payer = new MercadoPago\Payer();
  $payer->email = $contents['payer']['email'];
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


function gerarPix()
{
  require_once 'vendor/autoload.php';

  MercadoPago\SDK::setAccessToken("ENV_ACCESS_TOKEN");

  $payment = new MercadoPago\Payment();
  $payment->transaction_amount = 100;
  $payment->description = "Título do produto";
  $payment->payment_method_id = "pix";
  $payment->payer = array(
    "email" => "test@test.com",
    "first_name" => "Test",
    "last_name" => "User",
    "identification" => array(
      "type" => "CPF",
      "number" => "19119119100"
    ),
    "address" =>  array(
      "zip_code" => "06233200",
      "street_name" => "Av. das Nações Unidas",
      "street_number" => "3003",
      "neighborhood" => "Bonfim",
      "city" => "Osasco",
      "federal_unit" => "SP"
    )
  );

  $payment->save();
}

function gerarBoleto()
{
  require_once 'vendor/autoload.php';

  MercadoPago\SDK::setAccessToken("APP_USR-6955539560736178-082114-0efcc51e8009b7bdf34b32c02487d25c-307686468");

  $payment = new MercadoPago\Payment();
  $payment->transaction_amount = 100;
  $payment->description = "Cobrança de boleto";
  $payment->payment_method_id = "bolbradesco";
  $payment->payer = array(
    "email" => "aniplay111@gmail.com",
    "first_name" => "Douglas",
    "last_name" => "Silva",
    "identification" => array(
      "type" => "CPF",
      "number" => "10940816636"
    ),
    "address" =>  array(
      "zip_code" => "06233200",
      "street_name" => "Av. das Nações Unidas",
      "street_number" => "3003",
      "neighborhood" => "Bonfim",
      "city" => "Osasco",
      "federal_unit" => "SP"
    )
  );

  $payment->save();
  echo $payment->status;
  echo '<br />';
  echo $charge_id = $payment->id;
  echo '<br />';
  echo $payment->transaction_details->external_resource_url;
  echo '<hr />';
  var_dump($payment);
}
return gerarBoleto();
//https://www.mercadopago.com.br/payments/64511157064/ticket?caller_id=1494417253&payment_method_id=bolbradesco&payment_id=64511157064&payment_method_reference_id=10287385060&hash=5c5bbb95-a31e-44ee-b066-00dc51f9ddf8