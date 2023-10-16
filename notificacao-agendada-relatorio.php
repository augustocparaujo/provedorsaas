<?php
include('topo.php');
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
            <div class="col-lg-2">
              <h3 class="box-title">Notificações do mês</h3>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive no-padding">
              <table class="table table-hover" style="width:100%">
                  <thead>
                      <tr>
                          <th>#</th>
                          <th>COBRANÇA</th>
                          <th>NOME</th>
                          <th>CONTATO</th>
                          <th>DATA/DISPARO</th>
                          <th>STATUS</th>
                          <th>AÇÃO</th>
                      </tr>
                  </thead>
                  <tbody id="tabela">               
                  </tbody>
              </table></div>
              </form>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      <!--/.row-->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';
include('rodape.php');
?>
<script>
  $('.notificacoes').addClass('active menu-open');
  $('#notificacao-agendada-relatorio').addClass('active');

  $().ready(function() {
    tabela();
  })

  function tabela() {
    $.ajax({
      type: 'post',
      url: 'notificacoes-agendada-tabela.php',
      data: 'html',
      success: function(data) {
        $('#tabela').html(data);
      }
    });
    return false;
  };

  //função excluir
  function excluir(id) {
    $('#processando').modal('show');
    $.get('notificacao-excluir.php', {
      id: id
    }, function(data) {
      $('#processando').modal('hide');
      $('#retorno').show().fadeOut(2500).html(data);
      tabela();
    });
    return false;
  }
</script>