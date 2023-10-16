<?php
include('topo.php');
$query0 = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND recebercom=''") or die(mysqli_error($conexao));
if (mysqli_num_rows($query0) >= 1) {
  $dd0 = mysqli_fetch_array($query0);
}

$url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
if ($idempresa == '999') {
  $url = str_replace("bancos.php", "", $url . 'notificacoes-isp.php');
  $urlIugo = str_replace("notificacoes-isp.php", "", $url . 'notificacoes-isp-iugo.php');
  $urlMercado = str_replace("notificacoes-isp.php", "", $url . 'notificacoes-isp-mercadopago.php');
} else {
  $url = str_replace("bancos.php", "", $url . 'notificacoes.php');
  $urlIugo = str_replace("notificacoes.php", "", $url . 'notificacoes-iugo.php');
  $urlMercado = str_replace("notificacoes.php", "", $url . 'notificacoes-mercadopago.php');
}

$query = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND recebercom='Banco Iugo'") or die(mysqli_error($conexao));
if (mysqli_num_rows($query) >= 1) {
  $dd = mysqli_fetch_array($query);
}

echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">

      <form method="post" id="formDadosCobrancaJuno">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Banco IUGO (antigo juno)</h3>
              <div class="box-tools pull-right">
              	<button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Salvar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <input type="text" class="hidden" name="id" value="' . @$dd['id'] . '"/>
                  <label class="col-lg-4 col-md-4 col-sm-6">Receber com:
                      <input type="text" class="form-control" name="recebercom" value="Banco Iugo" readonly/>
                  </label>                
                  <label class="col-lg-12 col-md-12 col-sm-12">Token privado
                    <input type="text" class="form-control" name="tokenprivado" value="' . AspasForm(@$dd['tokenprivado']) . '"/>
                  </label>
                  <label class="col-lg-12 col-md-12 col-sm-12">URL de notificação de pagamento
                    <input type="text" class="form-control" name="url" value="https://augustocezar.com.br/provedorsaas/notificacaoes-iugo.php" readonly/>
                  </label>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      </form>';

$query2 = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND recebercom='Gerencianet'") or die(mysqli_error($conexao));
$dd2 = mysqli_fetch_array($query2);

echo '

      <form method="post" id="formDadosCobrancaGerencianet">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Banco Gerencianet</h3>
              <div class="box-tools pull-right">
              	<button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Salvar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <input type="text" class="hidden" name="id" value="' . @$dd2['id'] . '"/>

                <label class="col-lg-4 col-md-4 col-sm-6">Banco
                    <input type="text" class="form-control" name="recebercom" value="Gerencianet" readonly/>
                </label>
                <div class="row"></div><br />
                  <label class="col-lg-6 col-md-6 col-sm-6">Cliente id
                    <input type="text" class="form-control" name="clienteid" value="' . AspasForm(@$dd2['clienteid']) . '"/>
                  </label>
                  <label class="col-lg-6 col-md-6 col-sm-6">Cliente secret
                    <input type="text" class="form-control" name="clientesecret" value="' . AspasForm(@$dd2['clientesecret']) . '"/>
                  </label>
                  <label class="col-lg-6 col-md-6 col-sm-6">Chave PIX aleatória
                  <input type="text" class="form-control" name="chavepixaleatoria" value="' . AspasForm(@$dd2['chavepixaleatoria']) . '"/>
                </label>
                  <label class="col-lg-12 col-md-12 col-sm-12">URL de notificação de pagamento
                  <input type="text" class="form-control" name="url" value="https://augustocezar.com.br/provedorsaas/notificacaoes.php" readonly/>
                </label>               
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      </form>';

$query3 = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND recebercom='Mercadopago'") or die(mysqli_error($conexao));
$ddM = mysqli_fetch_array($query3);

echo '

      <form method="post" id="formBancoMercadoPago">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Banco Mercado Pago</h3>
              <div class="box-tools pull-right">
              	<button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Salvar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <input type="hidden" name="id" value="' . @$ddM['id'] . '"/>
            <label class="col-lg-4 col-md-4 col-sm-6">Banco
                <input type="text" class="form-control" name="recebercom" value="Mercadopago" readonly/>
            </label>            

                <label class="col-lg-6 col-md-6 col-sm-6">Formas de recebimento MP
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="pix"';
if (!empty(@$ddM['pix'])) {
  echo 'checked';
}
echo '>PIX
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input disabled type="checkbox" name="boleto"';
if (!empty(@$ddM['boleto'])) {
  echo 'checked';
}
echo '>Boleto (em desenvolvimento)
                            </label>
                        </div>
                        <div class="checkbox">
                        <label>
                            <input disabled type="checkbox" name="cartao"';
if (!empty(@$ddM['cartao'])) {
  echo 'checked';
}
echo '>Cartão (em desenvolvimento)
                        </label>
                    </div>
                    </div>
                </label>
              
                  <label class="col-lg-12">Token
                    <input type="text" class="form-control" name="tokenprivado" value="' . AspasForm(@$ddM['tokenprivado']) . '"/>
                  </label>
                  <label class="col-lg-12 col-md-12 col-sm-12">URL de notificação de pagamento
                    <input type="text" class="form-control" name="url" value="https://augustocezar.com.br/provedorsaas/notificacaoes-mercadopago.php" readonly/>
                  </label>   
                  <div class="row"></div><hr>
                  <h4>Ajuda</h4>
                  <p>Para criar TOKEN e acesso do mercado pago acessar links estão aqui embaixo. Para receber pix (Taxa deles R$ 0,99)</p>
                  <p><a href="https://www.mercadopago.com.br/developers/pt" target="_blank">Site mercado pago</a> | &emsp; 
                  <a href="https://www.youtube.com/watch?v=w7kyGZUrkVY" target="_blank">Video como criar TOKEN</a> | &emsp;
                  <code>URL de notificação para baixa automática: https://painel.mkgestor.com.br/notificacoes-mercadopago.php</code></p>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      </form>      
   
    

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';

include('rodape.php');
?>
<script>
  $('.parametros').addClass('active menu-open');
  $('#bancos').addClass('active');

  //formDadosCobrana
  $('#formDadosCobrancaJuno').submit(function() {
    $.post({
      type: 'post',
      url: 'dados-cobranca.php',
      data: $('#formDadosCobrancaJuno').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(2500).html(data);
        window.setTimeout(function() {
          location.reload(true);
        }, 2501);
      }
    });
    return false;
  });

  //formDadosCobrana
  $('#formDadosCobrancaGerencianet').submit(function() {
    $.post({
      type: 'post',
      url: 'dados-cobranca.php',
      data: $('#formDadosCobrancaGerencianet').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(2500).html(data);
        window.setTimeout(function() {
          location.reload(true);
        });
      }
    });
    return false;
  });

  //"formBancoMercadoPago
  $('#formBancoMercadoPago').submit(function() {
    $.post({
      type: 'post',
      url: 'dados-cobranca.php',
      data: $('#formBancoMercadoPago').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(2500).html(data);
        window.setTimeout(function() {
          location.reload(true);
        });
      }
    });
    return false;
  });
</script>