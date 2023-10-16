<?php
if (@$_GET['id'] == '') {
  echo '<script>location.href="https://www.google.com/";</script>';
  die();
}
@$id = $_GET['id'];
include('conexao.php');
include('funcoes.php');

echo '
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>mk-gestor | PRE- CADASTRO</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="shortcut icon" href="assets/images/gisp-logo-f-branco-128x115-1.png">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!--funcybox-->
  <link rel="stylesheet" href="plugins/fancybox/dist/jquery.fancybox.css"/> 
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
 </head>
 <body class="container">
<!-- Content Wrapper. Contains page content -->
<div>  
    <!-- Main content -->
    <section class="content">
    <form method="post" action="insert-cad-cliente.php" autocomplete="off">
    <div class="row">
        <div class="col-xs-12" style="background-color:#FFF5EE">
          <div class="box">
            <div class="box-header" style="align-items:center !important; text-align:center !important;">';
$query = mysqli_query($conexao, "SELECT user.logomarca FROM user WHERE idempresa='$id'");
if (mysqli_num_rows($query) >= 1) {
  $ret = mysqli_fetch_array($query);
  if (!empty($ret['logomarca'])) {
    echo '<img src="logocli/' . $ret['logomarca'] . '" width= "200px"/>';
  }
}
echo '
              <h2 style="font-family:Arial, Helvetica, sans-serif; font-weight: bold;"><center>Preencher todos os campo</center></h2>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <input type="text" class="form-control hidden" name="idempresa" value="' . $id . '"/>

                <label class="col-lg-4 col-md-4 col-sm-12  col-xs-12">Melhor dia de pagamento
                <input type="number" class="form-control" placeholder="Vencimento" name="vencimento" value="05" required/>
              </label>
              <label class="col-lg-2 col-md-4 col-sm-12  col-xs-12">Plano
                <select class="form-control" placeholder="Plano" name="plano" required>
                  <option value="">selecione</option>';
@$id = $_GET['id'];
$query2 = mysqli_query($conexao, "SELECT * FROM plano WHERE idempresa='$id'") or die(mysqli_error($conexao));
if (mysqli_num_rows($query2) >= 1) {
  while ($dd = mysqli_fetch_array($query2)) {
    echo '<option value="' . $dd['plano'] . '">' . $dd['plano'] . '</option>';
  }
}
echo '
                </select>
              </label>

              <label class="col-lg-2 col-md-2 col-sm-12  col-xs-12">Pessoa <small class="text-red">*obrigatório</small>
              <select type="text" class="form-control" name="tipo" id="tipo" required>
                <option value="Física">Física</option>
                <option value="Juridica">Juridica</option>
              </select>
              </label>
              <label class="col-lg-4 col-md-6 col-sm-12  col-xs-12">Nome
                <input type="text" class="form-control" placeholder="Nome" name="nome" required/>
              </label>
              <div class="pessoafisica">
                <label class="col-lg-2 col-md-6 col-sm-6  col-xs-12">CPF
                  <input type="text" class="form-control cpf2" placeholder="Apenas números" name="cpf"/>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-6  col-xs-12">RG
                  <input type="text" class="form-control" placeholder="número" name="rg"/>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-6  col-xs-12">Estado RG
                  <select type="text" class="form-control" name="rguf">';
foreach ($estadosbr as $item) {
  echo '<option value="' . $item . '">' . $item . '</option>';
}
echo '
                  </select>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-6  col-xs-12">Orgão emissor
                  <input type="text" class="form-control" placeholder="Orgão emissor" name="emissor"/>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-6  col-xs-12">Nascimento
                  <input type="text" class="form-control data" name="nascimento"/>
                </label>
              </div>
              <div class="pessoajuridica">
                <label class="col-lg-4 col-md-6 col-sm-12 col-xs-12">Fantasia
                  <input type="text" class="form-control" placeholder="Fantasia" name="fantasia"/>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">CNPJ
                  <input type="text" class="form-control cnpj" placeholder="Apenas nmeros" name="cnpj"/>
                </label>
              </div>
              <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">Contato/whatsapp
                <input type="text" class="form-control celular" placeholder="Apenas números" name="contato"/>
              </label>
              <label class="col-lg-4 col-md-6 col-sm-6 col-xs-12">E-mail
                <input type="text" class="form-control" placeholder="E-mail" name="email"/>
              </label>
               <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">Emitir nota
                <select type="text" class="form-control" name="emitirnota">
                	<option value="não">não</option>
                    <option value="sim">sim</option>
                </select>
              </label>
            <div class="row"></div><hr>
            <label class="col-lg-2 col-md-4 col-sm-4 col-xs-12">CEP <small class="text-red">*obrigatório</small>
              <input type="text" class="form-control cepBusca" placeholder="Apenas números" name="cep"/>
            </label>
            <label class="col-lg-8 col-md-8 col-sm-8 col-xs-12">Rua/Alameda/Avenida/etc <small class="text-red">*obrigatório</small>
              <input type="text" class="form-control enderecoBusca" placeholder="Rua/Alameda/Avenida/etc" name="endereco"/>
            </label>
            <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">Número <small class="text-red">*obrigatório</small>
              <input type="text" class="form-control" placeholder="Número" name="numero"/>
            </label>
            <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">Bairro <small class="text-red">*obrigatório</small>
              <input type="text" class="form-control bairroBusca" placeholder="Bairro" name="bairro"/>
            </label>
            <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">Múnicipio <small class="text-red">*obrigatório</small>
              <input type="text" class="form-control cidadeBusca" placeholder="Múnicipio" name="municipio"/>
            </label>
            <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">Estado <small class="text-red">*obrigatório</small>
              <input type="text" class="form-control ufBusca" placeholder="Estado" name="estado"/>
            </label>
            <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Complemento
              <input type="text" class="form-control" placeholder="Complemento" id="cpf" name="complemento"/>
            </label>
            <label class="col-lg-4 col-md-6 col-sm-12  col-xs-12">Latitude
            <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude"/>
            </label>
            <label class="col-lg-4 col-md-6 col-sm-12  col-xs-12">Longitude
              <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude"/>
            </label>
            <div class="row"></div>
            <div class="box-header" style="align-items:center !important; text-align:center !important;">
            <h3 style="font-family:Arial, Helvetica, sans-serif; font-weight: bold;"><center>Se estiver no local da instalação</center></h3>
             
                <a onclick="getLocation()" class="btn btn-success btn-lg">ENVIAR LOCALIZAÇÃO</a>

            </div>
            <div class="row"></div>            
            <center>
            <button type="submit" class="btn btn-primary btn-lg">CADASTRAR</button>
            </center>
            </label>
         <br>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      </form>      
    </section>
    <!-- /.content -->   

    </div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!--busca cep -->
<script src="dist/js/buscacep.js"></script>
<!--funcybox -->
<script src="plugins/fancybox/dist/jquery.fancybox.js"></script>
<!-- mascaras -->
<script src="dist/js/jquery.mask.js"></script>
<script src="dist/js/jquery.maskMoney.js"></script>
<script src="dist/js/meusscripts.js"></script>
</body>
</html>'; ?>
<script>
  //tipo pessoa
  $(function($) {
    $('#tipo').on('change', function() {
      var valor = ($(this).val());
      if (valor == 'Física') {
        $('.pessoafisica').show();
        $('.pessoajuridica').hide().removeAttr('required', true);
      } else {
        $('.pessoafisica').hide().removeAttr('required', true);
        $('.pessoajuridica').show();
      }
    }).trigger('change');
  });

  $().ready(function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
      x.innerHTML = "Seu browser não suporta Geolocalização.";
    }
  });

  function showPosition(position) {
    x = position.coords.latitude;
    y = position.coords.longitude;

    $('#latitude').val(x);
    $('#longitude').val(y);
  }

  function showError(error) {
    switch (error.code) {
      case error.PERMISSION_DENIED:
        x.innerHTML = "Usuário rejeitou a solicitação de Geolocalização."
        break;
      case error.POSITION_UNAVAILABLE:
        x.innerHTML = "Localização indisponível."
        break;
      case error.TIMEOUT:
        x.innerHTML = "A requisição expirou."
        break;
      case error.UNKNOWN_ERROR:
        x.innerHTML = "Algum erro desconhecido aconteceu."
        break;
    }
  }
</script>