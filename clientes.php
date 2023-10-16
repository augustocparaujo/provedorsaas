<?php
include('topo.php');
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
            <div class="col-lg-2">
              <h3 class="box-title">Clientes</h3>
              </div>
              <div class="col-lg-10">';
if (PermissaoCheck($idempresa, 'clientes-cadastrar', $iduser) == 'checked' || $_SESSION['tipouser'] == 'Admin') {
  echo '
                  <div class="col-lg-2 col-md-6 col-sm-12">  
              	  <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#ClienteCadastrar"><i class="fa fa-plus"></i> Cadastrar</button></div>';
}
echo '
                <div class="col-lg-2 col-md-6 col-sm-12">  <a href="clientes-bloqueados.php" target="_blanck"><button class="btn btn-danger btn-block"><i class="fa fa-trash"></i> Bloqueados</button></a></div>
                <div class="col-lg-2 col-md-6 col-sm-12">  <a href="clientes-cancelados.php" target="_blanck"><button class="btn btn-warning btn-block"><i class="fa fa-ban"></i> Cancelados</button></a></div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive no-padding" id="tabela"></div>
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
              <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Pessoa <small class="text-red">*obrigatrio</small>
                <select type="text" class="form-control" name="tipo" id="tipoPessoaCad" required>
                  <option value="Física">Física</option>
                  <option value="Jurídica">Jurídica</option>
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
  $('.clientes').addClass('active menu-open');
  $('#clientes-busca').addClass('active');
  //tabela
  function tabela() {
    $.get('clientes-tabela.php', function(data) {
      $('#tabela').show().html(data);
    });
    return false;
  }
  tabela();
  //formCadastrarCliente
  $('#formCadastroCliente').submit(function() {
    $('#ClienteCadastrar').modal('hide');
    $.post({
      type: 'post',
      url: 'clientes-update.php',
      data: $('#formCadastroCliente').serialize(),
      success: function(data) {
        $('#retorno').show().html(data);
      }
    });
    return false;
  });
  //excuir
  function excluirCliente(id) {
    $('#processando').modal('show');
    $.get('excluir-cliente.php', {
      id: id
    }, function(data) {
      $('#processando').modal('hide');
      $('#retorno').show().fadeOut(6000).html(data);
      tabela();
    });
    return false;
  };
  //abrir chamado
  function abrirChamado(idcliente) {
    $('#CadastrarChamado').modal('show');
    $.get('clientes-chamado-retorno.php', {
      idcliente: idcliente
    }, function(data) {
      $('#retornoChamado').show().html(data);
    })
    return false;
  }
  //salvar chamado
  $('#formCadastroChamado').submit(function() {
    $('#CadastrarChamado').modal('hide');
    $('#processando').modal('show');
    $.ajax({
      type: 'post',
      url: 'insert-chamado.php',
      data: $('#formCadastroChamado').serialize(),
      success: function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().fadeOut(6000).html(data);
      }
    });
    return false;
  });
  //chamar whatsapp
  function whatsapp(fone) {
    window.open('https://api.whatsapp.com/send?phone=55' + fone + '&text=Ol%C3%A1%2Ctudo+bem');
  }
  //tipo pessoa
  $(function($) {
    $('#tipoPessoaCad').on('change', function() {
      var valor = ($(this).val());
      if (valor == 'Física') {
        $('.pessoafisica').show();
        $('.pessoajuridica').hide().removeAttr('required', true);
      } else {
        $('.pessoafisica').hide().removeAttr('required', true);
        $('.pessoajuridica').show();
      }
    }).trigger('change');
  });
</script>