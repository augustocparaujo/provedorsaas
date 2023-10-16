<?php
include('topo.php');

echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">

    <div class="row">
        <div class="col-xs-12">
        <form method="post" id="formBusca">
        <div class="row">
          <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <input type="date" class="form-control" name="inicio" id="inicio"/>
          </label>
              <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <input type="date" class="form-control" id="fim "name="fim"/>
          </label>
          <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <button class="btn btn-primary btn-block"><i class="fa fa-search"></i> Buscar</button>
          </label>
          </div>
      </form>

      <br />

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Log de SMS</h3>
              <div class="box-tools hidden">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                    <tr>
                    <th>Nº</th>
                   <th>ID</th>
                    <th>Usuário</th>
                    <th>Data</th>
                    <th>Contato</th>
                    <th>Mensagem</th>
                    </tr>
                    </thead>
                    <tbody id="tabelaBusca"></tbody>
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

include('rodape.php'); ?>
<script>
$('.gestao').addClass('active menu-open');
$('#log-sms').addClass('active');
//formBusca
$('#formBusca').submit(function() {
    $('#cadastrarAcesso').modal('hide');
    $.post({
        type: 'post',
        url: 'log-sms-tabela.php',
        data: $('#formBusca').serialize(),
        success: function(data) {
            $('#tabelaBusca').show().html(data);
        }
    });
    return false;
});
</script>