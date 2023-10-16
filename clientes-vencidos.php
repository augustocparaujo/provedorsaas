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
              <h3 class="box-title">Vencidos</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>Situação</th>
                        <th>Nome</th>
                        <th>CPF/CNPJ</th>
                        <th>Vencimentos</th>
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
<!-- /.content-wrapper -->';

include('rodape.php'); ?>
<script>
    $('#clientes').addClass('active');
    //tabela
    $().ready(function(){ tabela(); });
    function tabela(){
        $('#processando1').modal('show');
        $.ajax({
            type:'post',
            url:'tab-vencidos.php',
            data:'html',
            success: function(data){
                $('#processando1').modal('hide');
                $('#tabela').show().html(data);
            }
        });
        return false;
    };
    //bloquear cliente
    function bloquearCliente(id){
      $.get('bloquear-cliente.php',{id:id},function(data){
      $('#retorno').show().fadeOut(3000).html(data);
        tabela();
     });
     return false;
    }
    //exibir cliente
    function exibir(id){
      window.location.href='exibir-cliente.php?id='+id;
    }
</script>