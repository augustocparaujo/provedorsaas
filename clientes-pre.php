<?php 
include('topo.php');
echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Pré-cadastros</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive no-padding">
              <table class="table table-hover" style="width:100%">
                  <thead>
                      <tr>
                          <th>#</th>
                          <th>Nome</th>
                          <th>CPF/CNPJ</th>
                          <th>Situação</th>
                          <th>Data</th>
                          <th>Ação</th>
                      </tr>
                  </thead>
                  <tbody id="tabela">
                  </tbody>
              </table></div>
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
    $('.clientes').addClass('active menu-open');
$('#clientes-pre').addClass('active');
    $(document).ready(function(){ tabela(); });
    function tabela(){
        $.ajax({
            type:'post',
            url:'clientes-pre-tabela.php',
            data:'html',
            success:function(data){
                $('#tabela').show().html(data);
            }
        });
        return false;
    }
    function excluir(id){
        var r = confirm("Deseja excluir?\nNão tem como reverter!");
        if (r == true) {
           $.get('cliente-excluir-pre.php',{id:id},function(data){
            $('#retorno').show().fadeOut(1500).html(data);
            tabela();
        });
        } 
        return false;
    }
</script>