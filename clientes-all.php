<?php 
include('topo.php');
$sql0 = mysqli_query($conexao,"SELECT cliente.id FROM cliente WHERE idempresa='$idempresa'");
@$rows = mysqli_num_rows($sql0);

echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content"> 

      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
            <div class="col-lg-2">
              <h3 class="box-title">Todos Clientes</h3>
              </div>
              <div class="col-lg-10"> ';           
             
                if(PermissaoCheck($idempresa,'clientes-cadastrar',$iduser) == 'checked' || $_SESSION['tipouser'] == 'Admin'){ echo'
                  <div class="col-lg-2 col-md-6 col-sm-12"> 
              	  <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#ClienteCadastrar"><i class="fa fa-plus"></i> Cadastrar</button></div>';
                }
                if(PermissaoCheck($idempresa,'clientes-excluir',$iduser) == 'checked' || $_SESSION['tipouser'] == 'Admin'){ echo'
                  <div class="col-lg-2 col-md-6 col-sm-12"> 
                  <button class="btn btn-danger btn-block" onclick="ExcluirCliente()"><i class="fa fa-trash"></i> Excluir</button></div>';
                }echo'
                <div class="col-lg-2 col-md-6 col-sm-12"> 
                <a href="clientes-bloqueados.php" class="btn btn-black btn-block"><i class="fa fa-trash"></i> Bloqueados</a></div>
                <div class="col-lg-2 col-md-6 col-sm-12"> 
                <a href="clientes-cancelados.php" class="btn btn-warning btn-block"><i class="fa fa-trash"></i> Cancelados</a></div>

              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <form method="post" id="FormExcluirCliente">

            <div class="table-responsive no-padding">
              <table class="table table-hover" style="width:100%">
                  <thead>
                      <tr>
                        <th>N</th>
                          <th>CX</th>
                          <th>Nome</th>
                          <th>CPF/CNPJ</th>
                          <th>Venc</th>
                          <th>#</th>
                      </tr>
                  </thead>
                  <tbody>';
                  $n = 1;
                    $query = mysqli_query($conexao,"SELECT * FROM cliente WHERE cliente.idempresa='$idempresa' AND cliente.situacao='Ativo' ORDER BY cliente.nome ASC") 
                    or die (mysqli_error($conexao));
                     if(mysqli_num_rows($query) >= 1){
                        while($dd = mysqli_fetch_array($query)){echo'
                          <tr>
                            <td>'.$n.'</td>
                              <td><input type="checkbox" class="meucheckbox" name="id[]" value="'.$dd['id'].'"></td>
                              <td>';
                              if($dd['situacao'] == 'Ativo'){echo'<a href="clientes-exibir.php?id='.$dd['id'].'" style="color:green;font-weight: bold;" title="'.@$dd['situacao'].'">'.$dd['nome'].'</a>'; }
                              else{echo'<a href="clientes-exibir.php?id='.$dd['id'].'" style="color:red; font-weight: bold;" title="'.@$dd['situacao'].'">'.$dd['nome'].'</a>';}
                              echo'
                              </td>
                              <td>'; if($dd['cnpj'] != ''){ echo $dd['cnpj']; }else{ echo $dd['cpf'];} echo'</td>
                              <td>'.@$dd['vencimento'].'</td>
                              <td  style="padding:2px !important; margin: 2px">';
                              if(PermissaoCheck($idempresa,'clientes-financeiro',$_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin'){echo'
                                  <a href="clientes-financeiro-exibir.php?id='.$dd['id'].'" title="receber"><i class=" fa fa-dollar fa-2x text-green"></i></a>&ensp;';
                              }
                              if(PermissaoCheck($idempresa,'clientes-chamado',$_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin'){echo'
                                  <a href="#" onclick="abrirChamado('.$dd['id'].')"><i class="fa fa-headphones fa-2x text-blue"></i></a>&ensp;';
                              }
                              if($dd['situacao'] == 'Ativo'){echo'
                              <a href="#" title="Bloquear" onclick="bloquearCliente('.$dd['id'].')"><i class="fa fa-user-times fa-2x text-red"></i></a>&ensp;
                              <a href="#" title="Derrubar" onclick="derrubar('.$dd['id'].')"><i class="fa fa-chain-broken fa-2x text-black"></i></a>';
                              }elseif($dd['situacao'] != 'Ativo'){echo'
                                <a href="#" title="Ativar" onclick="ativarCliente('.$dd['id'].')"><i class="fa fa-thumbs-up fa-2x text-green"></i></a>';
                              }
                              echo'
                              </td>
                          </tr>';
                          $n++;
                        }
                      }                
                    echo'                
                  </tbody>
              </table></div>
              </form>

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
<!-- /.content-wrapper -->

<!-- modal cliente cadastrar-->
<div class="modal" id="ClienteCadastrar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCadastroCliente">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
              <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Pessoa <small class="text-red">*obrigatório</small>
                <select type="text" class="form-control" name="tipo" id="tipo" required>
                  <option value="Física">Física</option>
                  <option value="Jurdica">Jurdica</option>
                </select>
              </label>
              <div class="row"></div>
	        		<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Nome
		        		<input type="text" class="form-control" placeholder="Nome" name="nome" required/>
		        	</label>
              <div class="pessoafisica">
              <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">CPF
              <input type="text" class="form-control" placeholder="Apenas números" name="cpf"/>
              </label>
              <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">RG
              <input type="text" class="form-control" placeholder="Apenas números" name="rg"/>
              </label>
              </div>
              <div class="pessoajuridica">
              <label class="col-lg-6 col-md-6 col-sm-12">Fantasia
              <input type="text" class="form-control" placeholder="Fantasia" name="fantasia"/>
              </label>
              <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">CNPJ
                <input type="text" class="form-control" placeholder="Apenas números" name="cnpj"/>
              </label>
              <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">IE
                <input type="text" class="form-control" placeholder="Apenas números" name="ie"/>
              </label>
              </div>
		        	<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Nascimento
			        	<input type="text" class="form-control data" placeholder="00-00-0000" name="nascimento"/>
		        	</label>
		        	<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Contato/whatsapp <small class="text-red">*obrigatório</small>
			        	<input type="text" class="form-control celular" placeholder="Apenas números" name="contato"/>
		        	</label>
		        	<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">E-mail
		        		<input type="email" class="form-control" placeholder="E-mail" name="email"/>
		        	</label>
				      <div class="row"></div><hr>
		        	<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">CEP <small class="text-red">*obrigatório</small>
			        	<input type="text" class="form-control cep cepBusca" placeholder="Apenas números" name="cep"/>
		        	</label>
		        	<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Rua/Alameda/Avenida/etc <small class="text-red">*obrigatório</small>
			        	<input type="text" class="form-control enderecoBusca" placeholder="Rua/Alameda/Avenida/etc" name="rua" required/>
		        	</label>
		        	<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Número <small class="text-red">*obrigatório</small>
			        	<input type="text" class="form-control" placeholder="Número" name="numero"/>
		        	</label>
		        	<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Bairro <small class="text-red">*obrigatório</small>
			        	<input type="text" class="form-control bairroBusca" placeholder="Bairro" name="bairro" required/>
		        	</label>
		        	<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Múnicipio <small class="text-red">*obrigatório</small>
			        	<input type="text" class="form-control cidadeBusca" placeholder="Múnicipio" name="municipio" required/>
		        	</label>
		        	<label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Estado <small class="text-red">*obrigatório</small>
			        	<input type="text" class="form-control ufBusca" placeholder="Estado" name="estado" required/>
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
<!-- modal cliente cadastrar-->

<!-- modal abrir chamado-->
<div class="modal" id="CadastrarChamado" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Abrir chamado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCadastroChamado" autocomplete="off">
      <div class="modal-body">
        	<div class="row" id="retornoChamado">
        	
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
<!-- modal cliente cadastrar-->
';
include('rodape.php');
?>
<script>
    $('#clientes').addClass('active');
    //formCadastrarCliente
    $('#formCadastroCliente').submit(function(){
      $('#ClienteCadastrar').modal('hide');
    	$.post({
    		type:'post',
    		url:'clientes-update.php',
    		data:$('#formCadastroCliente').serialize(),
    		success:function(data){
          $('#retorno').show().html(data);
    		}
    	});
    	return false;
    });
    //sincronizar clientes
    function sincronzarClientes(){
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'sincronizar-clientes.php',
        data:'html',
        success: function(data){
        $('#processando').modal('hide');
        $('#retorno').show().fadeOut(3000).html(data);
        //tabela();
      }
    });
      return false;
    }
    //excuir
    function ExcluirCliente(){
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'excluir-cliente.php',
        data:$('#FormExcluirCliente').serialize(),
        success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(3000).html(data);
          window.setTimeout(function() { history.go(); }, 3001);

        }
      });
      return false;
    };
    //abrir chamado
    function abrirChamado(idcliente){
      $('#CadastrarChamado').modal('show');
       $.get('clientes-chamado-retorno.php',{idcliente:idcliente},function(data){
        $('#retornoChamado').show().html(data);
       })
      return false;
    }
    //salvar chamado
    $('#formCadastroChamado').submit(function(){
      $('#CadastrarChamado').modal('hide');
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'insert-chamado.php',
        data:$('#formCadastroChamado').serialize(),
        success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(3000).html(data);
        }
      });
      return false;
    });
    //chamar whatsapp
    function whatsapp(fone){
      window.open('https://api.whatsapp.com/send?phone=55'+fone+'&text=Ol%C3%A1%2Ctudo+bem');
    }
    //tipo pessoa
    $(function($) {
    $('#tipo').on('change', function() {
        var valor = ($(this).val());
        if(valor == 'Física'){
          $('.pessoafisica').show();
          $('.pessoajuridica').hide().removeAttr('required', true);
        }else{
          $('.pessoafisica').hide().removeAttr('required', true);
          $('.pessoajuridica').show();
        }
      }).trigger('change');
    });
    //derrubar cliente
    function derrubar(id){
     $.get('derrubar-cliente-lista.php',{id:id},function(data){
      $('#retorno').show().fadeOut(3000).html(data);
     });
     return false;
    }
    //bloquear cliente
    function bloquearCliente(id){
      $.get('bloquear-cliente.php',{id:id},function(data){
      $('#retorno').show().fadeOut(3000).html(data);
      window.setTimeout(function() { history.go(); }, 3001);

     });
     return false;
    }
    //ativar cliente
    function ativarCliente(id){
      $.get('ativar-cliente.php',{id:id},function(data){
      $('#retorno').show().fadeOut(3000).html(data);
      window.setTimeout(function() { history.go(); }, 3001);
     });
     return false;
    }
</script>