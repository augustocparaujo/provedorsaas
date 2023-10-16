<?php 
include('topo.php');
echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
    <div class="row">
        <input type="text" class="hidden" name="idservidor" value="'.@$_GET['id'].'"/>
        <div class="col-lg-12 col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title">Servidor: '.$_GET['nome'].'</h3>
              <div class="box-tools pull-right">';              
                if($_SESSION['tipouser'] == 'Admin' OR PermissaoCheck($idempresa,'planos-cadastrar',$iduser) == 'checked'){echo' 
                  <button class="btn btn-primary" data-toggle="modal" data-target="#AddPlano"><i class="fa fa-plus"></i> Cadastrar</button>';
                }echo'
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">

                <table class="table table-hover">
                  <thead>
                  <tr>
                    <th>Plano</th>
                    <th>Endereço local</th>
                    <th>Endereço remoto</th>
                    <th>Valocidade</th>
                    <th>Valor</th>
                    <th>#</th>
                  </tr> 
                  </thead>
                  <tbody id="tabela"></tbody>            
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

<!-- modal plano add-->
<div class="modal" id="AddPlano" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title">Plano</h5>       
      </div>
      <form method="post" id="formAddPlano">
      <div class="modal-body">
        	<div class="row">
          <input type="text" class="hidden" name="servidor" value="'.$_GET['id'].'"required/>
          <label class="col-lg-12">Nome
            <input type="text" class="form-control" name="plano" required/>
          </label>
          <label class="col-lg-12">Velocidade (Download/Upload)
            <input type="text" class="form-control" name="velocidade" placeholder="Ex: 20M/20M"/>
          </label>
          <label class="col-lg-12">Valor
            <input type="text" class="form-control real" placeholder="Valor" name="valor" required/>
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
<!-- modal plano add-->

<!-- modal plano alterar-->
<div class="modal" id="PlanoAlterar" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title">Plano</h5>
      </div>
      <form method="post" id="formPlanoAlterar">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12" id="retornoPlano">	        	
	        	</div>
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
<!-- modal servidor alterar-->';

include('rodape.php'); ?>
<script>
     $('.parametros').addClass('active menu-open');
     $('#servidor').addClass('active');
    $().ready(function(){ tabela(); });
    function tabela(){
      let id = <?php echo $_GET['id']; ?>;
      $.get('tab-planos.php',{id:id},function(data){
        $('#tabela').show().html(data);
      }); 
      return false;      
    }
    //alterar plano
    function alterar(id){
      $('#PlanoAlterar').modal('show');
      $.get('retorno-plano.php',{id:id},function(data){
        $('#retornoPlano').show().html(data);
      });
      return false;
    }
    //excluir plano
    function excluir(id){
      var r = confirm("Deseja excluir?");
          if (r == true) {
      $('#processando').modal('show');
      $.get('excluir-plano.php',{id:id},function(data){
        $('#processando').modal('hide');
        $('#retorno').show().fadeOut(2500).html(data);
        tabela();
      });
    }
      return false;

    }
     //addPlano
     $('#formAddPlano').submit(function(){
      $('#AddPlano').modal('hide');
      $('#processando').modal('show');
    	$.post({
    		type:'post',
    		url:'insert-plano.php',
    		data:$('#formAddPlano').serialize(),
    		success:function(data){
                $('#processando').modal('hide');
                $('#retorno').show().fadeOut(2500).html(data);
                tabela();
    		}
    	});
    	return false;
    });
    //formPlanoAlterar
    $('#formPlanoAlterar').submit(function(){
      $('#PlanoAlterar').modal('hide');
      $('#processando').modal('show');
    	$.post({
    		type:'post',
    		url:'update-plano.php',
    		data:$('#formPlanoAlterar').serialize(),
    		success:function(data){
                $('#processando').modal('hide');
                $('#retorno').show().fadeOut(2500).html(data);
                tabela();
    		}
    	});
    	return false;
    });

</script>