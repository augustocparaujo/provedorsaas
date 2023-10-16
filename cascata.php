<?php

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
include_once('conexao.php');
include_once('funcoes.php');
$query = mysqli_query($conexao, "SELECT id,valor,obs,vencimento,link FROM cobranca WHERE id='$id'");
if (mysqli_num_rows($query) > 0) {
  $r = mysqli_fetch_array($query);
  $valor = Real($r['valor']);
  $vencimento = dataForm($r['vencimento']);
  $descricao = $r['obs'];
  $link = $r['link'];
} else {
  echo '<script>location.href="/";</script>';
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
  <title>Titulo</title>
  <link rel="shortcut icon" href="images/logo.png">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    .login-page,
    .register-page {
      -ms-flex-align: center;
      align-items: center;
      display: -ms-flexbox;
      display: flex;
      -ms-flex-direction: column;
      flex-direction: column;
      height: 100vh;
      -ms-flex-pack: center;
      justify-content: center;
      background: url(dist/img/fundo.jpg) no-repeat center center fixed;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="login-box-body">
      <center>
        <h4 class="text-red">Escolher a forma de pagamento</h4>
      </center>
      <form method="post" action="cobranca-gerar-mercadopago.php">
        <input type="hidden" name="id" value="2" />
        <div class="col-lg-6">
          <label>Valor</label>
          <input type="text" class="form-control rel" name="valor" value="<?php echo $valor; ?>" required readonly />
        </div>
        <div class="col-lg-6">
          <label>Vencimento</label>
          <input type="text" class="form-control" name="vencimento" value="<?php echo $vencimento; ?>" required readonly />
        </div>
        <div class="form-group has-feedback">
          <label>Descrição</label>
          <textarea rows="2" class="form-control" name="descricao" required readonly><?php echo $descricao; ?></textarea>
        </div>
        <!-- forma de pagamento -->

        <div class="form-group">
          <label>Forma de pagamento</label>
          <select type="text" class="form-control" id="formapagamento" name="formapagamento">
            <option value="PIX">PIX</option>
            <option value="Boleto">Boleto</option>
            <option value="Cartão">Cartão crédito (1x)</option>
          </select>
        </div>

        <div class="cartao" style="display: none">
          <div class="form-group">
            <label>Nome no cartão</label>
            <input type="text" class="form-control cartao" name="nomecartao" value="Augusto C P Araujo" />
          </div>
          <div class="form-group">
            <label>CPF ou CNPJ</label>
            <input type="text" class="form-control numeros cartao" value="12345678909" name="cpf_cnpj" />
          </div>
          <div class="form-group">
            <label>Número cartão</label>
            <input type="text" class="form-control numeros cartao" value="5031433215406351" name="numerocartao" />
          </div>
          <div class="form-group">
            <label>Validade mês e ano</label>
            <input type="text" class="form-control cartao" name="validade" min="2" max="2" value="11/25" placeholder="01/23" />
          </div>
          <div class="form-group">
            <label>CVV</label>
            <input type="text" class="form-control numeros cartao" name="codigocvv" value="123" min="3" max="3" required />
          </div>
          <div class="row"></div>
          <p>Bandeiras:
            <img src="dist/img/credit/visa.png" alt="Visa" width="10%">
            <img src="dist/img/credit/mastercard.png" alt="Mastercard" width="10%">
          </p>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <button type="submit" class="btn bg-navy btn-block btn-lg" id="aguarde">GERAR COBRANÇA</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <?php
      if ($link != '') {
        echo ' <br />
    <div class="row">
      <div class="col-xs-12">
        <a href="' . @$link . '" target="_blank">
        <button class="btn btn-block btn-lg" style="background:#D8CB0B"> CLIQUE AQUI PARA PAGAR</button></a>
        <center><p class="text-danger">Após o pagamento a baixa será realizada automaticamente</p></center>
      </div>
      <!-- /.col -->
    </div>';
      }


      ?>
    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="dist/js/jquery.mask.js"></script>
  <script src="dist/js/jquery.maskMoney.js"></script>
  <script src="dist/js/meusscripts.js"></script>

</body>

</html>
<script>
  $('.numeros').keyup(function() {
    $(this).val(this.value.replace(/\D/g, ''));
  });
  //
  $('#formapagamento').change(function() {
    let forma = $(this).val();
    if (forma == 'Cartão') {
      $('.cartao').show().attr('required', true);
    } else {
      $('.cartao').hide().attr('required', false);
    };
  });
  //
  $('#form').submit(function() {
    $('#aguarde').show().attr('disabled', true).text('Aguarde, pagamento...');
    $.ajax({
      type: 'post',
      url: 'cobranca-gerar-mercadopago.php',
      data: $('#form').serialize(),
      success: function(data) {
        $('#aguarde').hide().removeAttr('disabled', true);
        history.go();
      }
    });
    return false;
  });
</script>