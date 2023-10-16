<?php
include('topo.php');
echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<div class="row"></div><br>
    <!-- Main content -->
    <section class="content">
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-lg-12"> 
          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Servidores</h3>
            <div class="box-tools pull-right">';
            if(@$_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'servidor-cadastrar',$iduser) == 'checked'){echo'
                <button class="btn btn-primary" data-toggle="modal" data-target="#ServidorCadastrar"><i class="fa fa-plus"></i> Cadastrar</button>';
            }echo'
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="tabela">
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <!-- modal servidor cadastrar-->
<div class="modal" id="ServidorCadastrar" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCadastroServidor">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	        		<label class="col-lg-12">Nome <small class="text-red">*obrigatório</small>
		        		<input type="text" class="form-control" placeholder="Nome" name="nome" required/>
		        	</label>
		        	<label class="col-lg-12">IP:PORTA <small class="text-red">*192.168.0.1:8728</small>
			        	<input type="text" class="form-control" placeholder="IP" name="ip" required/>
		        	</label>
		        	<label class="col-lg-12">Usuário <small class="text-red">*obrigatório</small>
			        	<input type="text" class="form-control" placeholder="Usuário" name="usuario"/>
		        	</label>
		        	<label class="col-lg-12">Senha <small class="text-red">*obrigatório</small>
			        	<input type="text" class="form-control" placeholder="Senha" name="senha"/>
		        	</label>   
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
<!-- modal servidor cadastrar-->

<!-- modal servidor alterar-->
<div class="modal" id="ServidorAlterar" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAlterarServidor">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12" id="retornoServidor">	        	
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

include('rodape.php');
?>
<script>
     $('.parametros').addClass('active menu-open');
  $('#servidor').addClass('active');
      //tabCli
      $().ready(function(){ tabela(); })
    function tabela(){
      $('#processandosrv').modal('show');
      $.ajax({
      type:'post',
      url:'tab-servidores.php',
      data:'html',
      success:function(data){
        $('#processandosrv').modal('hide');
        $('#tabela').html(data);
      }
    });
    return false;
    };
    //formCadastrarCliente
    $('#formCadastroServidor').submit(function(){
      $('#ServidorCadastrar').modal('hide');
      $('#processando').modal('show');
    	$.post({
    		type:'post',
    		url:'insert-servidor.php',
    		data:$('#formCadastroServidor').serialize(),
    		success:function(data){
                $('#processando').modal('hide');
                $('#retorno').show().html(data);
                tabela();
    		}
    	});
    	return false;
    });
    //excluir servidor
    function excluirServidor(id){
        $('#processando').modal('show');
        $.get('excluir-servidor.php',{id:id},function(data){
            $('#processando').modal('hide');
            $('#retorno').show().html(data);
            tabela();
        });
        return false;
    }
    //alterar servidor
    function alterarServidor(id){
        $('#ServidorAlterar').modal('show');
        $.get('retorno-servidor.php',{id:id},function(data){
            $('#retornoServidor').show().html(data);
        });
        return false;
    }
    //formAlterarServidor
    $('#formAlterarServidor').submit(function(){
      $('#ServidorAlterar').modal('hide');
      $('#processando').modal('show');
    	$.post({
    		type:'post',
    		url:'update-servidor.php',
    		data:$('#formAlterarServidor').serialize(),
    		success:function(data){
                $('#processando').modal('hide');
                $('#retorno').show().html(data);
              tabela();
    		}
    	});
    	return false;
    });
    //sincronizar planos
    function sincronzarPlanos(id){
      $('#processando').modal('show');
      $.get('sincronizar-planos.php',{id:id},function(data){
        $('#processando').modal('hide');
        $('#retorno').show().html(data);
      });
      return false;
    }
    //sincronizar clientes
    function sincronzarClientes(id){
      $('#processando').modal('show');
      $.get('sincronizar-clientes.php',{id:id},function(data){
        $('#processando').modal('hide');
        $('#retorno').show().html(data);
      });
      return false;
    }
      //sincronizar clientes
    function statusServer(id){
      $('#processando').modal('show');
      $.get('servidor-status.php',{id:id},function(data){
        $('#processando').modal('hide');
        $('#retorno').show().html(data);
      });
      return false;
    }
    //teste de conexão entre 30 segundo
    //setInterval(function () { tabela(); }, 30000);
    </script>