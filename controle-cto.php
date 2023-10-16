<?php
include('topo.php');
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
    <div class="row">
    <form method="post" id="filtra">
        <div class="col-lg-2 col-md-2 col-sm-12">CTO
          <input type="text" class="form-control" name="cto">
        </div>  
        <div class="col-lg-2 col-md-2 col-sm-12 CPF0">Cliente
          <input type="text" class="form-control" name="cliente">
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12"></br>
            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Buscar</button>
        </div>
      </form>
        <div class="col-lg-2 col-md-2 col-sm-12"></br>
          <a href="controle-cto.php" class="btn btn-danger btn-block"><i class="fa fa-eraser"></i> Limpar</a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12"></br>
            <a href="#" class="btn btn-info btn-block" data-toggle="modal" data-target="#cadastrar"><i class="fa fa-plus"></i> Cadastrar</a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12"></br>
            <a href="controle-mapa.php" target="_black" class="btn bg-navy btn-block"><i class="fa fa-street-view"></i> Mapa</a>
        </div>
    
    </div>
    <br>    
    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Controle de CTO</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>CTO/PORTA</th>
                        <th>Empresa</th>
                        <th>Cliente</th>
                        <th>Local/UF</th>
                        <th>Long/Lati</th>
                        <th>#</th>
                    </tr>
                </tbody>
                <tfoot id="tabela">
            </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';

echo '
<!-- modal cadastrar-->
<div class="modal" id="cadastrar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCadastrar">
      <div class="modal-body">
        	<div class="row">
                    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">CTO
                        <select type="text" class="form-control" name="cto">
                            <option value="">CTO</option>';
$queryCto = mysqli_query($conexao, "SELECT cto FROM controle_cto WHERE idempresa='$idempresa' GROUP BY cto ORDER BY cto ASC");
if (mysqli_num_rows($queryCto) > 0) {
  while ($r = mysqli_fetch_array($queryCto)) {
    echo '<option value="' . $r['cto'] . '">' . $r['cto'] . '</option>';
  }
}
echo '
                        </select>
                    </label>
                    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">CTO Nova
                        <input type="text" class="form-control" placeholder="Nova CTO" name="novacto"/>
                    </label>
                    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Porta
                        <input type="text" class="form-control" placeholder="Porta" name="porta"/>
                    </label>
                    <!-- empresa cto porta cliente longitude latitude estado localizacao Nome -->
                    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Empresa
                        <input type="text" class="form-control" placeholder="Empresa" name="empresa"/>
                    </label>
                    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Cliente
                        <input type="text" class="form-control" placeholder="Cliente" name="cliente"/>
                    </label>
                    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Localização/Endereço
                        <input type="text" class="form-control" placeholder="Próx.Venda José" name="localizacao"/>
                    </label>
                    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Estado
                        <select type="text" class="form-control" name="estado"/>
                            <option value="">Estado</option>';
foreach ($estadosbr as $mes) {
  echo '<option value="' . $mes . '">' . $mes . '</option>';
  # code...
}
echo '
                        </select>
                    </label>
                    <label class="col-lg-6 col-md-6 col-sm-12 col-xs-6">Longitude
                        <input type="text" class="form-control" name="longitude"/>
                    </label>
                    <label class="col-lg-6 col-md-6 col-sm-12 col-xs-6">Latitude
                        <input type="text" class="form-control" name="latitude"/>
                    </label>
        	</div>       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- modal cliente cadastrar-->';

echo '
<!-- modal alterar-->
<div class="modal" id="alterar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAlterar">
      <div class="modal-body" id="retornoControle">
             
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- modal alterar-->';
include('rodape.php'); ?>
<script>
  $('.cto').addClass('active menu-open');
  $('#controle-cto').addClass('active');
  //
  $().ready(function() {
    tabela();
  });
  //tabela
  function tabela() {
    $.get('controle-cto-tabela.php', function(data) {
      $('#tabela').show().html(data);
    });
    return false;
  }
  //busca tabela
  $('#filtra').submit(function() {
    $.ajax({
      type: 'POST',
      url: 'controle-cto-tabela.php',
      data: $('#filtra').serialize(),
      success: function(data) {
        $('#tabela').show().html(data);
      }
    });
    return false;
  });

  //cadastrar
  $('#formCadastrar').submit(function() {
    $.ajax({
      type: 'POST',
      url: 'controle-cto-update.php',
      data: $('#formCadastrar').serialize(),
      success: function(data) {
        $('#cadastrar').modal('hide');
        $('#retorno').show().fadeOut(2500).html(data);
        tabela();
      }
    });
    return false;
  });

  //retorno/update
  function editar(id) {
    $.get('controle-cto-retorno.php', {
      id: id
    }, function(data) {
      $('#alterar').modal('show');
      $('#retornoControle').show().html(data);
    });
    return false;
  }
  $('#formAlterar').submit(function() {
    $.ajax({
      type: 'POST',
      url: 'controle-cto-update.php',
      data: $('#formAlterar').serialize(),
      success: function(data) {
        $('#alterar').modal('hide');
        $('#retorno').show().fadeOut(2500).html(data);
        tabela();
      }
    });
    return false;
  });

  //delete
  function excluir(id) {
    let destroy = 1;
    $.get('controle-cto-update.php', {
      id: id,
      destroy: destroy
    }, function(data) {
      $('#retorno').show().fadeOut(2500).html(data);
      tabela();
    });
    return false;
  }
</script>