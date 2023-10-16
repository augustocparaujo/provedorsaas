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
              <h3 class="box-title">Usuários</h3>
              <div class="box-tools pull-right">';
              if($_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'usuarios-cadastrar',$iduser) == 'checked'){ echo'
              	<button class="btn btn-primary" data-toggle="modal" data-target="#CadastrarUsuario"><i class="fa fa-plus"></i> Cadastrar</button>';
              }echo'
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
<div class="modal" id="CadastrarUsuario" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCadastroUsuario">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
	        		<label class="col-lg-12 col-md-12 col-sm-12">Nome <small class="text-red">*</small>
		        		<input type="text" class="form-control" placeholder="Nome" name="nome" required/>
		        	</label>
		        	<label class="col-lg-12 col-md-12 col-sm-12">Login <small class="text-red">(Padão: nome.sobrenome)</small>
			        	<input type="text" class="form-control" placeholder="Login" name="login" required/>
		        	</label>
		        	<label class="col-lg-12 col-md-12 col-sm-12">Senha <small class="text-red">*</small>
			        	<input type="text" class="form-control" placeholder="Senha" name="senha" required/>
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
<!-- modal cliente cadastrar-->';

include('rodape.php');
?>
<script>
     $('.parametros').addClass('active menu-open');
    $('#usuarios').addClass('active');
    //tabusuarios
    $().ready(function(){ tabela(); })
    function tabela(){
      $.ajax({
      type:'post',
      url:'tab-usuarios.php',
      data:'html',
      success:function(data){
        $('#tabela').html(data);
      }
    });
    return false;
    };
    //formCadastrarUsuario
    $('#formCadastroUsuario').submit(function(){
      $('#CadastrarUsuario').modal('hide');
    	$.post({
    		type:'post',
    		url:'insert-usuario.php',
    		data:$('#formCadastroUsuario').serialize(),
    		success:function(data){
          $('#retorno').show().fadeOut(6000).html(data);
          tabela();
    		}
    	});
    	return false;
    });
    //ativar usuário
    function ativar(id,situacao){
      $.get('ativar-usuario.php',{id:id,situacao:situacao},function(data){
        $('#retorno').show().fadeOut(6000).html(data);
        tabela();
      });
      return false;
    }
    //excluir usuário
    function excluirUsuario(id){
      var r = confirm("Deseja excluir?");
        if (r == true) {
          $.get('excluir-usuario.php',{id:id},function(data){
            $('#retorno').show().fadeOut(6000).html(data);
            tabela();
          });
          return false;
        }
    }
</script>