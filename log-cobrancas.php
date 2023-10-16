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
              <h3 class="box-title">Log de cobranças</h3>

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
            <div class="box-body table-responsive" id="tabela">
             
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
    $('#log-cobranca').addClass('active');
    //tabela log cobrança
    $().ready(function(){ tabela(); });
    function tabela(){
      $.ajax({
        type:'post',
        url:'tab-log-cobrancas.php',
        data:'html',
        success:function(data){
          $('#tabela').show().html(data);
        }
      });
      return false;
    }
</script>