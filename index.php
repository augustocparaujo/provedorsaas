<?php
include('topo.php');
include('dashboard.php');

$servidores = [
  "UOL" => "www.uol.com.br",
  "TIKTOK" => "www.tiktok.com",
  "KWAI" => "www.kwai.com",
  "TWITTER" => "www.twitter.com",
  "CLOUDFLARE" => "www.cloudflare.com",
  "TERRA" => "www.terra.com.br",
  "INSTAGRAM" => "www.instagram.com",
  "GOOGLE" => "www.google.com",
  "YOUTUBE" => "www.yahoo.com",
  "FACEBOOK" => "www.facebook.com",
];

echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content" style="font-size:75%; !important; ">
      <!-- linha 1-->
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">';

if (PermissaoCheck($idempresa, 'dashboard-online', $iduser) == 'checked' or $_SESSION['tipouser'] == 'Admin') {
  echo '
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-gray">
            <a href="servidor.php"><span class="info-box-icon text-black"><i class="fa fa-link"></i></span></a>
                <div class="info-box-content mause">
                    <span class="info-box-text">Clientes</span>
                    <span class="info-box-number"></span>
                        <div class="progress">
                          <div class="progress-bar" style="width: 50%"></div>
                        </div>
                        <span class="progress-description">
                          Online
                        </span>
                </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->';
}

if (PermissaoCheck($idempresa, 'dashboard-clientestotal', $iduser) == 'checked' or $_SESSION['tipouser'] == 'Admin') {
  echo '          
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green">
            <a href="clientes.php">
              <span class="info-box-icon text-black"><i class="fa fa-users"></i></span>
               </a>
              <div class="info-box-content">
                <span class="info-box-text">Clientes</span>
                <span class="info-box-number">' . $totClientes . '</span>
                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                    <span class="progress-description">
                      Cadastrado
                    </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->';
}

if (PermissaoCheck($idempresa, 'dashboard-conlinecancelados', $iduser) == 'checked' or $_SESSION['tipouser'] == 'Admin') {
  echo '          
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-yellow">
              <a href="clientes-bloqueados.php"><span class="info-box-icon text-black"><i class="fa fa-user-times"></i></span> </a> 
              <div class="info-box-content">
                <span class="info-box-number">' . @$totCancelados . '</span>
                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                    <span class="progress-description">
                      Bloqueados
                    </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>               
          <!-- /.col --> ';
}

if (PermissaoCheck($idempresa, 'dashboard-chamados', $iduser) == 'checked' or $_SESSION['tipouser'] == 'Admin') {
  echo '          
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-red">
            <a href="chamados.php"><span class="info-box-icon"><i class="fa fa-headphones text-black"></i></span> </a> 
              <div class="info-box-content">
                <span class="info-box-text">Chamados</span>
                <span class="info-box-number">' . $totChamados . '</span>

                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                    <span class="progress-description">
                      30 Dias
                    </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>               
          <!-- /.col --> ';
}

echo '
        </div>
      </div>';

if (PermissaoCheck($idempresa, 'dashboard-cobrancas', $iduser) == 'checked' or $_SESSION['tipouser'] == 'Admin') {
  echo '
        <!-- financeiro -->
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="col-md-3 col-sm-6 col-xs-12 mause">
              <div class="info-box bg-gray">
                <span class="info-box-icon"><i class="ion ion-stats-bars"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">R$</span>
                  <span class="info-box-number">' . Real($rett['totalcobrancas']) . '</span>
  
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="progress-description">
                    Total cobranças
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>          
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box bg-blue mause">
                <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">R$</span>
                  <span class="info-box-number">' . Real($retabertas['totalabertas']) . '</span>
  
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="progress-description">
                    Em aberto (ano)
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>                
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box bg-red">
                <span class="info-box-icon"><i class="fa fa-pie-chart"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">R$</span>
                  <span class="info-box-number">' . Real($retatraso['totalatraso']) . '</span>
  
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="progress-description">
                    Em atraso (até hoje)
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-area-chart"></i></span>
  
                <div class="info-box-content">
                  <span class="info-box-text">R$</span>
                  <span class="info-box-number">' . Real($retrecebidas['totalrecebidas']) . '</span>
  
                  <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                  </div>
                  <span class="progress-description">
                    Efetuados
                  </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
          </div>
        </div>';
}
echo '
    <div class="row">     

        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="box box-primary">
          <div class="box-header">
          <h3 class="box-title">Chamados recentes</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
          <div class="col-md-12 col-sm-12 col-xs-12">
          <table class="table table-hover" style="font-size: 12px !important">
          <thead><tr>
          <th>#</th>
          <th>Tipo</th>
          <th>Nome</th>
          <th>Data</th>
          <th>Situação</th>
          </tr>              
          </thead>
          <tbody id="tabelaChamado2"></tbody>
          </table>
          </div>
          </div>
          <!-- /.box-body -->
          </div>
        </div>
 
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="box box-warning">
          <div class="box-header">
          <h3 class="box-title">Meus Chamados</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
          <div class="col-md-12 col-sm-12 col-xs-12">
          <table class="table table-hover" style="font-size: 12px !important">
          <thead><tr>
          <th>#</th>
          <th>Tipo</th>
          <th>Nome</th>
          <th>Data</th>
          <th>Situação</th>
          </tr>              
          </thead>
          <tbody id="tabelaMeusChamados"></tbody>
          </table>
          </div>
          </div>
          <!-- /.box-body -->
          </div>
        </div>

    </div>

    <div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12">
    <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Teste de Rotas (Portas:80/443)</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="table-responsive">
        <table class="table no-margin">
          <thead>
          <tr>
            <th>#</th>
            <th>Rota</th>
            <th>Status</th>
            <th>Tempo/Resp.</th>
          </tr>
          </thead>
          <tbody>';

$n = 1;
foreach ($servidores as $servidor => $ip) {
  // $retorno = shell_exec("C:\Windows\system32\ping -n 1 $ip"); - Este codigo não funciona em servidor compartilhado por questões de segurança.
  $retorno = @fsockopen($ip, 80, $errCode, $errStr, 2);

  // if (preg_match("/tempo</", $retorno) || preg_match("/tempo=/", $retorno))  - Este codigo não funciona em servidor compartilhado por questões de segurança.
  if ($retorno) {
    $status = "respondendo";
    $cor = "success";
  } else {
    $status = "sem resposta";
    $cor = "danger";
  }
  echo '
            <tr>
            <td>' . $n . '</td>
            <td>' . $servidor . '</td>
            <td><span class="label label-' . $cor . '">' . $status . '</span></td>
            <td><i class="fa fa-clock"></i> 2 seg</td>
          </tr>
            ';




  $n++;
}
echo ';
       
          </tbody>
        </table>
      </div>
      <!-- /.table-responsive -->
    </div>
  </div></div>

    
    </div>



<h5>Pré-cadastro para cliente: https://painel.mkgestor.com.br/cad-cliente.php?id=' . $_SESSION['idempresa'] . '</h5>
<h5>Link: <a href="https://painel.mkgestor.com.br/cad-cliente.php?id=' . $_SESSION['idempresa'] . '" target="_blank"> Pré-cadastro</a></h5>
<h5 class="text-red">Cadastro rápido</h5>
<h5>Cadastro rápido cliente: https://painel.mkgestor.com.br/cad-cliente-rapido.php?id=' . $_SESSION['idempresa'] . '</h5>
<h5>Link: <a href="https://painel.mkgestor.com.br/cad-cliente-rapido.php?id=' . $_SESSION['idempresa'] . '" target="_blank"> Cadastro rápido</a></h5>
<h5>
  Acesso do cliente: https://acesso.mkgestor.com.br/login.php
  <br />
  <p class="text-red">Para acessar a área do cliente é obrigatório ter cpf ou cnpj, sendo a senha os 6 primeiros digitos.</p>
  </h5



    </section>
    <!-- /.content -->
</div>


<!-- /.content-wrapper -->';
include('rodape.php'); ?>
<script>
  $().ready(function() {
    tabelaChamado2();
  })

  function tabelaChamado2() {
    $.ajax({
      type: 'post',
      url: 'tab-chamado-recentes.php',
      data: 'html',
      success: function(data) {
        $('#tabelaChamado2').show().html(data);
      }
    });
    return false;
  };

  $().ready(function() {
    tabelaMeusChamados();
  })

  function tabelaMeusChamados() {
    $.ajax({
      type: 'post',
      url: 'tab-chamado-tecnico.php',
      data: 'html',
      success: function(data) {
        $('#tabelaMeusChamados').show().html(data);
      }
    });
    return false;
  };
</script>