<style>
  .titulo{
    font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-weight: bold;
  }
  .divisor{
    border-left-style:solid;
    border-color: #CD5C5C;
  }
</style>
<?php 
include('topo.php');
@$idempresa = @$_SESSION['idempresa'];
$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM usuarios WHERE idempresa='$_SESSION[idempresa]' AND id='$id'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  
     <form method="post" id="formAtualizarUsuarioStaff">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Atualizar cadastro</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-default" onclick="history.back();"><i class="fa  fa-chevron-left"></i> Voltar</button>
              	<button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Atualizar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <input type="text" class="hidden" name="id" value="'.$dd['id'].'"/>
              <label class="col-lg-4 col-md-4 col-sm-6">Nome
                <input type="text" class="form-control" name="nome" value="'.@$dd['nome'].'"/>
              </label>
              <label class="col-lg-4 col-md-4 col-sm-6">E-mail
                <input type="text" class="form-control" name="email" value="'.@$dd['email'].'"/>
              </label>
              <label class="col-lg-4 col-md-4 col-sm-6">CPF
                <input type="text" class="form-control cpf2" name="cpf" value="'.@$dd['cpf'].'"/>
              </label>
              <label class="col-lg-4 col-md-4 col-sm-6">Contato
                <input type="text" class="form-control celular" name="contato" value="'.@$dd['contato'].'"/>
              </label>     
              <label class="col-lg-2 col-md-2 col-sm-3">Login
                <input type="text" class="form-control" value="'.@$dd['logintxt'].'" disabled/>
              </label>   ';
              if(PermissaoCheck($idempresa,'usuarios-login',$id) == 'checked' || $_SESSION['tipouser'] == 'Admin'){  echo'  
              <label class="col-lg-2 col-md-2 col-sm-3">Alterar login
                <input type="text" class="form-control" placeholder="Login" name="login"/>
              </label>';
              }
              if(PermissaoCheck($idempresa,' usuarios-alterarsenha',$id) == 'checked' || $_SESSION['tipouser'] == 'Admin'){  echo'     
              <label class="col-lg-2 col-md-2 col-sm-3">Alterar senha
                <input type="password" class="form-control" placeholder="Senha" name="senha"/>
              </label>';
              }echo'                                      
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      </form>';
      if(PermissaoCheck($idempresa,'usuarios-permissoes',$id) == 'checked' || $_SESSION['tipouser'] == 'Admin'){
      echo'
      <form method="post" id="formPermissaoUsuarioStaff">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Permissões</h3>
              <div class="box-tools pull-right">
              	<button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Atualizar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <input type="text" class="hidden" name="usuario" value="'.$dd['id'].'"/>
            <div class="col-xs-12">
            <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo">  
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'clientes',$id).' value="clientes"> Clientes
              </label>
              <br/>
              <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'clientes-cadastrar',$id).' value="clientes-cadastrar"> Cadastrar<br/>
              <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'clientes-financeiro',$id).' value="clientes-financeiro"> Financeiro<br/>
              <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'cliente-relatorio',$id).' value="cliente-relatorio"> Relatrio cliente<br/>
              <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'clientes-online',$id).' value="clientes-online"> Cliente online<br/>
              
              

              <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'clientes-excluir',$id).' value="clientes-excluir"> Excluir<br/>
              <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'clientes-chamado',$id).' value="clientes-chamado"> Chamado<br/>
              <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'clientes-alterar-chamado',$id).' value="clientes-alterar-chamado"> Alterar Chamado<br/>
              <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'clientes-whatsapp',$id).' value="clientes-whatsapp"> Whatsapp<br/>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo"> 
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'alertas',$id).' value="alertas"> Alertas
                </label>
                <br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'alertas-enviar',$id).' value="alertas-enviar"> Enviar<br/>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo"> 
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'planos',$id).' value="planos"> Planos
                </label>
                <br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'planos-sincronicar',$id).' value="planos-sincronicar"> Sincronicar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'planos-cadastrar',$id).' value="planos-cadastrar"> Cadastrar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'planos-editar',$id).' value="planos-editar"> Editar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'planos-excluir',$id).' value="planos-excluir"> Excluir<br/>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo"> 
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'rotas',$id).' value="rotas"> Rotas
                </label>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo"> 
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'controle-caixa',$id).' value="controle-caixa"> Controle de caixa
                </label>
                <br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'controle-caixa-cadastrar',$id).' value="controle-caixa-cadastrar"> Cadastrar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'controle-caixa-excluir',$id).' value="controle-caixa-excluir"> Excluir<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'controle-caixa-exibir',$id).' value="controle-caixa-exibir"> Exibir<br/>

                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'cadastrar-gasto',$id).' value="cadastrar-gasto"> Cadastrar gasto<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'alterar-gasto',$id).' value="alterar-gasto"> Alterar gasto<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'excluir-gasto',$id).' value="excluir-gasto"> Excluir gasto<br/>

              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo"> 
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'cobrancas',$id).' value="cobrancas"> Cobranças
                </label>
                <br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'cobranca-receber',$id).' value="cobranca-receber"> Receber<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'cobranca-excluir',$id).' value="cobranca-excluir"> Excluir<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'cobranca-editar',$id).' value="cobranca-editar"> Editar<br/>
              </div>
              </div>
              <hr>
              <div class="row">
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo"> 
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'emissao-de-recibo',$id).' value="emissao-de-recibo"> Recibo
                </label>
                <br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'recibos-emitir',$id).' value="recibos-emitir"> Emitir<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'recibos-imprimir',$id).' value="recibos-imprimir"> Imprimir<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'recibos-editar',$id).' value="recibos-editar"> Editar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'recibos-excluir',$id).' value="recibos-excluir"> Excluir<br/>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo"> 
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'conf-de-cobranca',$id).' value="conf-de-cobranca"> Conf. de cobrança
                </label>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo"> 
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'usuarios',$id).' value="usuarios"> Usuários
                </label>
                <br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'usuarios-cadastrar',$id).' value="usuarios-cadastrar"> Cadastrar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'usuarios-editar',$id).' value="usuarios-editar"> Editar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'usuarios-ativar',$id).' value="usuarios-ativar"> Ativar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'usuarios-excluir',$id).' value="usuarios-excluir"> Excluir<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'usuarios-permissoes',$id).' value="usuarios-permissoes"> Permissões<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'usuarios-login',$id).' value="usuarios-login"> Alterar login<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'usuarios-alterarsenha',$id).' value="usuarios-alterarsenha"> Alterar senha<br/>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
                <label class="titulo"> 
                  <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'servidor',$id).' value="servidor"> Servidor
                </label>
                <br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'servidor-cadastrar',$id).' value="servidor-cadastrar"> Cadastrar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'servidor-editar',$id).' value="servidor-editar"> Editar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'servidor-excluir',$id).' value="servidor-excluir"> Excluir<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'servidor-conectar',$id).' value="servidor-conectar"> Conectar<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'servidor-logs',$id).' value="servidor-logs"> Logs<br/>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
                <label class="titulo"> 
                  <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'dashboard',$id).' value="dashboard"> Dashboard<br/>
                  <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'dashboard-online',$id).' value="dashboard-online"> Online<br/>
                  <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'dashboard-clientestotal',$id).' value="dashboard-clientestotal"> Clientes total<br/>
                  <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'dashboard-conlinecancelados',$id).' value="dashboard-conlinecancelados"> Cancelados<br/>
                  <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'dashboard-chamados',$id).' value="dashboard-chamados"> Chamados<br/>
                  <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'dashboard-cobrancas',$id).' value="dashboard-cobrancas"> Cobrancas
                </label>
              </div>

              <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 divisor">
              <label class="titulo"> 
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'estoque',$id).' value="estoque"> Estoque
              </label>
              <br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'cadastrar-item',$id).' value="cadastrar-item"> Cadastrar item<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'alterar-categoria',$id).' value="alterar-categoria"> Alterar categoria<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'alterar-fornecedor',$id).' value="alterar-fornecedor"> Alterar fornecedor<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'exibir-item',$id).' value="exibir-item"> Exibir item<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'alterar-item',$id).' value="alterar-item"> Alterar item<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'saida-item',$id).' value="saida-item-conectar"> Saída item<br/>
                <input type="checkbox" name="permissoes[]" '.PermissaoCheck($idempresa,'excluir-item',$id).' value="excluir-item"> Excluir item<br/>
              </div>
              
              </div>
              </div>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      </form>';
      }echo'        
     
      <!--/.row-->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';

include('rodape.php');
?>
<script>
     $('#usuarios').addClass('active');
    //formAtualizarUsuarioStaff
    $('#formAtualizarUsuarioStaff').submit(function(){
      $('#processando').modal('show');
    	$.post({
    		type:'post',
    		url:'update-usuario-staff.php',
    		data:$('#formAtualizarUsuarioStaff').serialize(),
    		success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(6000).html(data);
          window.setTimeout(function() {
              history.go();
          }, 3001);
    		}
    	});
    	return false;
    });
    //formPermissaoUsuarioStaff
      $('#formPermissaoUsuarioStaff').submit(function(){
      $('#processando').modal('show');
    	$.post({
    		type:'post',
    		url:'update-permissao-staff.php',
    		data:$('#formPermissaoUsuarioStaff').serialize(),
    		success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(6000).html(data);
          window.setTimeout(function() {
              history.go();
          }, 3001);
    		}
    	});
    	return false;
    });
    
</script>