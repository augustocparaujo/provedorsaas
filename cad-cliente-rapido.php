<?php
if ($_GET['id'] == '') {
  echo '<script>location.href="https://www.google.com/";</script>';
  die();
}

include('conexao.php');
include('funcoes.php');
@$id = @$_GET['id'];


echo '
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Mk-Gestor V1.0.0 | PRE- CADASTRO</title>
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
    <form method="post" action="insert-cad-cliente-rapido.php" autocomplete="off">
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
              <label class="col-lg-4 col-md-6 col-sm-12  col-xs-12">Nome
                <input type="text" class="form-control" placeholder="Nome" name="nome" required/>
              </label>
              <label class="col-lg-4 col-md-6 col-sm-12  col-xs-12">Whatsapp
                <input type="number" class="form-control" placeholder="Apenas números" name="contato" required/>
              </label>
              <label class="col-lg-4 col-md-6 col-sm-12  col-xs-12">Melhor dia de pagamento
                <input type="number" class="form-control" placeholder="Vencimento" name="vencimento" required/>
              </label>
              <label class="col-lg-4 col-md-6 col-sm-12  col-xs-12">Plano
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
</body>
</html>';
