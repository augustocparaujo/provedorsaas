<?php
include('topo.php');
$query0 = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));
if (mysqli_num_rows($query0) >= 1) {
  $dd0 = mysqli_fetch_array($query0);
}

$url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
if ($idempresa == '999') {
  $url = str_replace("configuracoes-cobranca.php", "", $url . 'notificacoes-isp.php');
} else {
  $url = str_replace("configuracoes-cobranca.php", "", $url . 'notificacoes.php');
}
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  

     <form method="post" id="formDadosCobranca">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Parâmetros</h3>
              <div class="box-tools pull-right">
              	<button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Salvar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <input type="text" class="hidden" name="id" value="' . @$dd0['id'] . '"/>
                <label class="col-lg-3 col-md-3 col-sm-6">Após vencimento
                  <input type="number" class="form-control" name="aposvencimento" value="' . @$dd0['aposvencimento'] . '" required/>
                </label>
                <label class="col-lg-3 col-md-3 col-sm-6">Dias p/ desconto
                  <input type="text" class="form-control" placeholder="Exemplo:5 dias antes" name="diasdesconto" value="' . @$dd0['diasdesconto'] . '"/>
                </label>
                <label class="col-lg-3 col-md-3 col-sm-6">Desconto
                  <input type="text" class="form-control real" name="valordesconto" value="' . Real(@$dd0['valordesconto']) . '"/>
                </label>
                <label class="col-lg-3 col-md-3 col-sm-6">Multa 
                  <input type="text" class="form-control real" name="multaapos" value="' . Real(@$dd0['multaapos']) . '"/>
                </label>
                <label class="col-lg-3 col-md-3 col-sm-6">Juros
                  <input type="text" class="form-control real" name="jurosapos" value="' . Real(@$dd0['jurosapos']) . '"/>
                </label>
                <label class="col-lg-3 col-md-3 col-sm-6">Dias/bloqueio 
                  <input type="number" class="form-control" name="diasbloqueio" value="' . @$dd0['diasbloqueio'] . '"/>
                </label>
                <label class="col-lg-3 col-md-3 col-sm-6">Bloqueio
                    <select type="text" class="form-control" name="bloqueioautomatico" required>';
if (!empty($dd0['bloqueioautomatico'])) {
  echo '<option value="' . $dd0['bloqueioautomatico'] . '">' . $dd0['bloqueioautomatico'] . '</option>';
}
echo '
                        <option value="sim">sim</option>
                        <option value="não">não</option>
                    </select>
                </label>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      </form>';

$query0 = mysqli_query($conexao, "SELECT * FROM config_sms WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));
$ddn = mysqli_fetch_array($query0);
echo '
      
      <!-- notificação -->
      <form method="post" id="formDadosCobrancaWhatsapp">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Notificação Whatsapp</h3>
              <div class="box-tools pull-right">
              	<button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Salvar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">   
            
            <h4>Empresa para Contratar API: <a href="https://api.whatsapp.com/send?phone=5561993172736" target="_blank">Clique aqui</a></h4>
            <hr>    
                <input type="hidden" class="form-control" name="id" value="' . @$ddn['id'] . '"/>
                <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">API
                  <input type="text" class="form-control" name="api" value="douglas" readonly/>
                </label>     
                <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Número whatsapp
                  <input type="text" class="form-control" name="numero" value="' . AspasForm(@$ddn['instancia']) . '"/>
                </label>
                <label class="col-lg-6 col-md-6 col-sm-12 col-xs-12">Token
                  <input type="text" class="form-control" name="token" value="' . AspasForm(@$ddn['token']) . '"/>
                </label>   
                <div class="row"></div><br />               

                <label class="col-lg-2 col-md-2 col-sm-6 col-xs-12">Antes do vencimento
                  <input type="number" class="form-control" name="antes" value="' . @$ddn['antes'] . '"/>
                </label>
                <label class="col-lg-2 col-md-2 col-sm-6 col-xs-12">Após do vencimento
                  <input type="number" class="form-control" name="depois" value="' . @$ddn['depois'] . '"/>
                </label>
                <label class="col-lg-12  col-sm-12">Váriaveis para adicionar na notificação: {{nomecliente}}, {{descricao}}, {{valor}}, {{vencimento}}, {{link}}, {{mercadopago}}.
                  <textarea rows="16" class="form-control" placeholder="Texto" name="texto">' . AspasForm(@$ddn['texto']) . '</textarea>
                </label>     
                            
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      <!--sms-->
      </form> ';

$query4 = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE idempresa='$idempresa'") or die(mysqli_error($conexao));
$dd4 = mysqli_fetch_array($query4);

echo '
      <form method="post" id="formDadosCobranca2">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">SMS</h3>
              <div class="box-tools pull-right">
              	<button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Salvar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">   
            
            <h4>Site da empresa para contratar pacote de SMS: <a href="https://sistema.smsnet.com.br/login" target="_blank">SMS NET</a>
            <p><small class="text-red">
              OBS: Para enviar sms ou whatsapp precisar ter contratado API da antes (sms=0,01, what=0,06) /
              Habiliar sms ou whatsapp e configurar </small></p>            
            </h4>
            <hr>            
                <label class="col-lg-4 col-md-6 col-sm-6 col-xs-12">Usuário
                  <input type="text" class="form-control" name="usuariosms" value="' . AspasForm(@$dd4['usuariosms']) . '"/>
                </label>
                <label class="col-lg-2 col-md-2 col-sm-6 col-xs-12">Senha
                  <input type="text" class="form-control" name="senhasms" value="' . AspasForm(@$dd4['senhasms']) . '"/>
                </label>
                
                <div class="row"></div><br />               

                <label class="col-lg-2 col-md-2 col-sm-6 col-xs-12">Antes do vencimento
                  <input type="number" class="form-control" name="antessms" value="' . @$ddsms4['antes'] . '"/>
                </label>
                <label class="col-lg-2 col-md-2 col-sm-6 col-xs-12">Após do vencimento
                  <input type="number" class="form-control" name="depoissms" value="' . @$ddsms4['depois'] . '"/>
                </label>
                
                <label class="col-lg-12  col-sm-12">Váriaveis para adicionar na notificação: {{nomecliente}}, {{descricao}}, {{valor}}, {{vencimento}}, {{link}}.
                  <textarea rows="12" class="form-control" placeholder="Texto" name="texto">' . AspasForm(@$ddsms['texto']) . '</textarea>
                </label>
                <div class="row"></div><br />
                <div class="col-12">
                <code>
                  OBS:SMS só envia 160 caracteres em uma mensagem, pois é o limite que o celular recebe por vez. Se tiver mais de 160 ele enviara numa segunda mensagem.
                </code>  
                </div>
                
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      <!--sms-->
      </form>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';

include('rodape.php');
?>
<script>
  $('.parametros').addClass('active menu-open');
  $('#configuracoes').addClass('active');

  //formDadosCobrana
  $('#formDadosCobranca').submit(function() {
    $.post({
      type: 'post',
      url: 'dados-cobranca-parametros.php',
      data: $('#formDadosCobranca').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(2500).html(data);
      }
    });
    return false;
  });

  //formDadosCobrancaWhatsapp
  $('#formDadosCobrancaWhatsapp').submit(function() {
    $.post({
      type: 'post',
      url: 'dados-api-zap.php',
      data: $('#formDadosCobrancaWhatsapp').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(2500).html(data);
      }
    });
    return false;
  });


  //formDadosCobrana2
  $('#formDadosCobranca2').submit(function() {
    $.post({
      type: 'post',
      url: 'dados-cobranca-sms.php',
      data: $('#formDadosCobranca2').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(2500).html(data);
      }
    });
    return false;
  });
</script>