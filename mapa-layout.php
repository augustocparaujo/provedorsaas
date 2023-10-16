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
            <div class="col-lg-4">
              <h3 class="box-title">Layout mapa</h3>
              </div>
              <div class="col-lg-8">
              	<button class="btn btn-primary hidden" data-toggle="modal" data-target="#CadastrarChamado"><i class="fa fa-plus"></i> Cadastrar</button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body"></div>
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
$('.mapa').addClass('active menu-open');
$('#mapa-layout').addClass('active');
</script>