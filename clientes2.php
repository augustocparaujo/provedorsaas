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
              <h3 class="box-title">Clientes mk-gestor</h3>
              <div class="box-tools pull-right">
              	<button class="btn btn-primary" data-toggle="modal" data-target="#cadastrarAcesso"><i class="fa fa-plus"></i> Cadastrar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="tabela"></div>
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
<!-- /.content-wrapper -->

<!-- modal cliente cadastrar-->
<div class="modal" id="cadastrarAcesso" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCadastroSistema">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	        		<label class="col-lg-12 col-md-12 col-sm-12">Nome <small class="text-red">*</small>
		        		<input type="text" class="form-control" placeholder="Nome" name="nome" required/>
		        	</label>
		        	<label class="col-lg-12 col-md-12 col-sm-12">Login <small class="text-red">*</small>
			        	<input type="text" class="form-control" placeholder="Login" name="login" required/>
		        	</label>
		        	<label class="col-lg-12 col-md-12 col-sm-12">Senha <small class="text-red">*</small>
			        	<input type="text" class="form-control" placeholder="Senha" name="senha" required/>
		        	</label>
              <p class="text-red">Admin = Acesso total, Demo = acesso apenas a empresa vinculada e seus usuários</p>
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
<!-- modal cliente cadastrar-->';

include('rodape.php');
?>
<script>
   $('.gestao').addClass('active menu-open');
    $('#clientes2').addClass('active');
    //tabCli
    $().ready(function(){ tabela(); })
    function tabela(){
      $.ajax({
      type:'post',
      url:'tab-clientes2.php',
      data:'html',
      success:function(data){
        $('#tabela').html(data);
      }
    });
    return false;
    };
    //formCadastrarSistema
    $('#formCadastroSistema').submit(function(){
      $('#cadastrarAcesso').modal('hide');
    	$.post({
    		type:'post',
    		url:'insert-usuario-sistema.php',
    		data:$('#formCadastroSistema').serialize(),
    		success:function(data){
          $('#retorno').show().fadeOut(6000).html(data);
          tabela();
    		}
    	});
    	return false;
    });
    //ativar cliente mk
    function ativar(id,s){
      $.get('ativar-cliente-mk.php',{id:id,s:s},function(data){
        $('#retorno').show().fadeOut(6000).html(data);
        tabela();
      });
      return false;
    }
    //reset senha cliente
    function resetSenha(id){
      $.get('update-reset-senha-mk.php',{id:id},function(data){
        $('#retorno').show().html(data);
        tabela();
      });
      return false;
    }
        //excluir
        function excluir2(id){
      var r = confirm("Deseja excluir ISP, permanetemente?");
          if (r == true) {
            $('#processando').modal('show');
            $.get('excluir-usuario2.php',{id:id},function(data){
              $('#processando').modal('hide');
              $('#retorno').show().fadeOut(6000).html(data);
              tabela();
            });
          }
          return false;
    }
</script>