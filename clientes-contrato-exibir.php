<?php
include('topo.php');
$id = $_GET['id'];
$query = mysqli_query($conexao, "SELECT * FROM contratos WHERE id='$id'") or die(mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
$idcontrato = $dd['id'];

echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
    <form method="post" id="formAtualizarCliente">
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
              <h3 class="box-title">Dados contrato</h3>
              </div>
              <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
              <a href="clientes-exibir.php?id=' . $dd['idcliente'] . '" class="btn btn-info" style="margin:2px"><i class="fa fa-file-text-o"></i> Dados cliente</a>
              <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Atualizar</button>
              <a href="clientes-financeiro-exibir.php?id=' . $dd['idcliente'] . '" class="btn btn-primary"><i class="fa fa-dollar"></i> Financeiro</a>
            </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">N° contrato
            <input type="text" class="form-control" name="id" value="' . $dd['id'] . '" readonly/>
            </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Situação
              <select type="text" class="form-control" name="situacao" required>';
if (!empty($dd['situacao'])) {
  echo '<option value="' . $dd['situacao'] . '">' . $dd['situacao'] . '</option>';
}
echo '
                <option value="">Selecionar</option>
                <option value="Ativo">Ativo</option>
                <option value="Bloqueado">Bloqueado</option>
                <option value="Cancelado">Cancelado</option>
              </select>
              </label>
              <label class="col-lg-4 col-md-6 col-sm-12 col-xs-12">Plano <small class="text-red">*obrigatrio</small>
                <select type="text" class="form-control" name="plano" required>';
if (!empty($dd['plano'])) {
  echo '<option value="' . $dd['plano'] . '">(' . $dd['plano'] . '-' . $dd['nomeservidor'] . ') ' . $dd['nomeplano'] . '</option>';
} else {
  echo '<option value="">selecionar</option>';
};

planosServidor($idempresa);
echo '
                  
                </select>
              </label> 
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Login pppoe
                <input type="text" class="form-control" placeholder="Login pppoe" value="' . Aspasform($dd['login']) . '" name="login" required/>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Senha pppoe
                <input type="text" class="form-control" placeholder="Senha pppoe" value="' . $dd['senha'] . '" name="password" required/>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Servio
                <select type="text" class="form-control" placeholder="Serviço" name="service" required>';
if ($dd['service'] != '') {
  echo '<option value="' . $dd['service'] . '">' . $dd['service'] . '</option>';
} else {
  echo '<option value="pppoe">pppoe</option>';
}
echo '
                  <option value="pppoe">pppoe</option>
                  <option value="sstp">sstp</option>
                  <option value="pptp">pptp</option>
                </select>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">IP
                <input type="text" class="form-control" placeholder="Sugestão: 172.16.0.1" name="ip" value="' . $dd['ip'] . '"/>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Porta
                <input type="text" class="form-control" placeholder="Porta" name="porta" value="' . $dd['porta'] . '"/>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">MAC
                <input type="text" class="form-control" placeholder="MAC" name="mac" value="' . $dd['mac'] . '"/>
              </label>            
            
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Data ativação
                <input type="date" class="form-control" name="ativacao" value="' . date($dd['ativacao']) . '" required/>
              </label>       
           
            <div class="row"></div><hr>
            <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">CEP
              <input type="text" class="form-control cepBusca" placeholder="Apenas números" name="cep" value="' . $dd['cep'] . '"/>
            </label>
            <label class="col-lg-4 col-md-6 col-sm-12 col-xs-12">Rua/Alameda/Avenida/etc
              <input type="text" class="form-control enderecoBusca" placeholder="Rua/Alameda/Avenida/etc" name="rua" value="' . $dd['rua'] . '" required/>
            </label>
            <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Número
              <input type="text" class="form-control" placeholder="NÚmero" name="numero" value="' . $dd['numero'] . '"/>
            </label>
            <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Bairro
              <input type="text" class="form-control bairroBusca" placeholder="Bairro" name="bairro" value="' . $dd['bairro'] . '" required/>
            </label>
            <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Municipio
              <input type="text" class="form-control cidadeBusca" placeholder="Municipio" name="municipio" value="' . $dd['municipio'] . '" required/>
            </label>
            <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Estado
              <input type="text" class="form-control ufBusca" placeholder="Estado" name="estado" value="' . $dd['estado'] . '" required/>
            </label>
             <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Cdigo IBGE
              <input type="text" class="form-control ibgeBusca" placeholder="IBGE" name="ibge" value="' . $dd['ibge'] . '"/>
            </label>            
            <label class="col-lg-4 col-md-6 col-sm-12 col-xs-12">Complemento
              <input type="text" class="form-control" placeholder="Complemento" id="cpf" name="complemento" value="' . AspasForm($dd['complemento']) . '"/>
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Latitude
            <input type="text" class="form-control" placeholder="Latitude" name="latitude" value="' . AspasForm($dd['latitude']) . '"/>
          </label>
          <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Longitude
            <input type="text" class="form-control" placeholder="Longitude" name="longitude" value="' . AspasForm($dd['longitude']) . '"/>
          </label> 
          <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Link localização
          <input type="text" class="form-control" id="link2" value="https://painel.mkgestor.com.br/acesso/cad-cliente-contrato-localizacao.php?idcliente=' . $id . '&idempresa=' . $idempresa . '&idcontrato=' . $idcontrato . '"/>
          </label>
          <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><center>
          <button type="button" class="btn btn-default" onclick="copiarTexto1()"><i class="fa fa-copy"></i> Copiar</button>
          </label></center>  
          <!--
          <div class="row"></div><hr>
          <h4>Equipamento em comodato</h4>

          <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">N/S
          <input type="text" class="form-control" placeholder="" name="nsecomodato" value="' . $dd['nsecomodato'] . '"/>
          </label>
          <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">Modelo
          <input type="text" class="form-control" placeholder="Modelo" name="modelocomodato" value="' . $dd['modelocomodato'] . '"/>
          </label>
          <label class="col-lg-2 col-md-2 col-sm-12 col-xs-12">MAC
          <input type="text" class="form-control" placeholder="" name="maccomodato" value="' . $dd['maccomodato'] . '"/>
          </label>-->
            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      </form>  
      
    </section>
    <!-- /.content -->    
</div>
<!-- /.content-wrapper -->';
include('rodape.php'); ?>
<script>
  $('.clientes').addClass('active menu-open');

  $('#clientes').addClass('active');
  //formAtualizarCliente
  $('#formAtualizarCliente').submit(function() {
    $('#processando').modal('show');
    $.ajax({
      type: 'post',
      url: 'clientes-contrato-update.php',
      data: $('#formAtualizarCliente').serialize(),
      success: function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().html(data);
        window.setTimeout(function() {
          location.reload();
        }, 3000);
      }
    });
    return false;
  });

  function copiarTexto1() {
    var tt = document.getElementById('link2');
    tt.select();
    document.execCommand("Copy");
  }
</script>