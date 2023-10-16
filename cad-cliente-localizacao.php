<?php
include('conexao.php');
include('funcoes.php');
@$idcliente= $_GET['idcliente'];
@$id = $_GET['idempresa'];
echo'
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GISP 1.0</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="shortcut icon" href="dist/img/icon-72x72.png">
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
    <form method="post" action="insert-cad-cliente-localizacao.php" autocomplete="off">
    <div class="row">
        <div class="col-xs-12" style="background-color:#FFF5EE">
          <div class="box">
            <div class="box-header" style="align-items:center !important; text-align:center !important;">';
            $query = mysqli_query($conexao,"SELECT user.logomarca FROM user WHERE idempresa='$id'");
            if(mysqli_num_rows($query) >= 1){
              $ret = mysqli_fetch_array($query);
              if(!empty($ret['logomarca'])){ echo '<img src="logocli/'.$ret['logomarca'].'" width= "200px"/>';} 
            }echo'
              <h2 style="font-family:Arial, Helvetica, sans-serif; font-weight: bold;"><center>Enviar localização</center></h2>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <input type="text" class="form-control hidden" name="idempresa" value="'.$id.'"/>
                <input type="text" class="form-control hidden" name="idcliente" value="'.$idcliente.'"/>
  
            <center>
            <label class="col-lg-4 col-md-6 col-sm-12">Latitude
            <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude"/>
            </label>
            <label class="col-lg-4 col-md-6 col-sm-12">Longitude
              <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude"/>
            </label>
            <label class="col-lg-4 col-md-6 col-sm-12"><br>
            <button type="submit" class="btn btn-primary">Enviar</button>

            </label>
            </center>
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
</html>'; ?>
<script>

$().ready(function getLocation()
  {
  if (navigator.geolocation)
    {
    navigator.geolocation.getCurrentPosition(showPosition,showError);
    }
  else{x.innerHTML="Seu browser não suporta Geolocalização.";}
  });
function showPosition(position)
  {
    x = position.coords.latitude;
    y = position.coords.longitude;

    $('#latitude').val(x);
    $('#longitude').val(y);
  }
function showError(error)
  {
  switch(error.code)
    {
    case error.PERMISSION_DENIED:
      x.innerHTML="Usuário rejeitou a solicitação de Geolocalização."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML="Localização indisponível."
      break;
    case error.TIMEOUT:
      x.innerHTML="A requisição expirou."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML="Algum erro desconhecido aconteceu."
      break;
    }
  }
</script>