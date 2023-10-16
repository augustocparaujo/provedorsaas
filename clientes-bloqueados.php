<?php
include('topo.php');
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Bloqueados</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>N</th>
                        <th>Nome</th>
                        <th>CPF/CNPJ</th>
                        <th>Status</th>
                        <th>Plano</th>
                        <th>Usuário</th>
                        <th>Data/Atualizado</th>
                        <th>Situação</th>
                        <th>#</th>
                    </tr>
                </tbody>
                <tfoot id="tabela"></tfoot>
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
<!-- /.content-wrapper -->
<!-- modal processando-->
<div class="modal fade in" id="processando1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm">   
      <div class="modal-body">
        <center>
        <h4>Aguarde</h4>
          <div class="overlay">
            <i class="fa fa-refresh fa-spin fa-2x"></i>
          </div> 
          <h4>Verificando clientes na RB(s)</h4>    
          </center> 
      </div>
    </div>
  </div>
</div>
<!-- modal processando-->';

include('rodape.php'); ?>
<script>
  $('#clientes').addClass('active');
  //tabela
  function tabela() {
    $('#processando1').modal('show');
    $.ajax({
      type: 'post',
      url: 'tab-bloqueados.php',
      data: 'html',
      success: function(data) {
        $('#processando1').modal('hide');
        $('#tabela').show().html(data);
      }
    });
    return false;
  };
  tabela();
  //exibir cliente
  function exibir(id) {
    window.location.href = 'clientes-exibir.php?id=' + id;
  }
</script>