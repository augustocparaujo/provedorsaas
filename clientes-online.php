<?php 
include('topo.php');

echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">

    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Clientes online <i class="text-red">PARA ACESSAR O ROTEADOR PRECISA PRECISA ESTÁ NA MESMA REDE</i></h3>

              <div class="hidden box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="tabela">             
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

<div class="modal" id="Modalconsumo" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h5 class="modal-title">Consumo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" id="callbackRecebido"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>';

include('rodape.php'); ?>
<script>
    $('.clientes').addClass('active menu-open');
    $('#clientes-online').addClass('active');
    //clientes online
    $().ready(function(){ tabela(); });
    function tabela(){
        $('#processando').modal('show');
        $.ajax({
        type:'post',
        url:'tab-clientes-status.php',
        data:'html',
        success:function(data){
             $('#processando').modal('hide');
            $('#tabela').show().html(data);
        }
        });
        return false;
    };
    //derrubar cliente
    function derrubar(id){
      $.get('derrubar-cliente.php',{id:id},function(data){
        $('#retorno').show().fadeOut(9000).html(data);
        tabelaStatusClientes();
      });
      return false;
    };
    //consumo cliente
    function consumoC(login,id){
      $('#Modalconsumo').modal('show');
      window.setInterval(function(){ 
      $.get('retorno-consumo-cliente.php',{login:login,id:id},function(data){
        $('#callbackRecebido').show().html(data);
      });
      return false;
        }, 1500);
    };
</script>