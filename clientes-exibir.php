<?php
include('topo.php');
$id = $_GET['id'];
$query = mysqli_query($conexao, "SELECT * FROM cliente WHERE cliente.id='$id'") or die(mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
$idempresa = $dd['idempresa'];

echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
    <form method="post" id="formAtualizarCliente">
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header">
            <div class="row">
              <div class="col-lg-2 col-md-6 col-sm-12">
              <h3 class="box-title">Cliente</h3>
              </div>
              </div>
              <div class="row">
              <div class="col-lg-12 col-md-6 col-sm-12">
                <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Atualizar</button>
                <a href="clientes-financeiro-exibir.php?id=' . $id . '" class="btn btn-primary"><i class="fa fa-dollar"></i> Financeiro</a>
                <a onclick="abrirChamado(' . $id . ')" class="btn bg-olive"><i class="fa fa-headphones"></i> Abrir chamado</a>     
                <a href="clientes-chamados.php?id=' . $id . '" class="btn btn-info "><i class="fa fa-headphones"></i> Chamados</a>  
            
              </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Código
                <input type="text" class="form-control" id="id" name="id" value="' . $id . '" readonly/>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Situação
              <select type="text" class="form-control" name="situacao" required>';
if (!empty($dd['situacao'])) {
  echo '<option value="' . $dd['situacao'] . '">' . $dd['situacao'] . '</option>';
}
echo '
                <option value="">Selecionar</option>
                <option value="Ativo">Ativo</option>
                <option value="Bloqueado">Bloqueado</option>
                <option value="Cancelado">Cancelado</option>
              </select>
              </label>

              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Banco
              <select type="text" class="form-control" name="banco" required>';
if (!empty($dd['banco'])) {
  echo '<option value="' . $dd['banco'] . '">' . $dd['banco'] . '</option>';
}
echo '
                <option value="">Selecionar</option>
                <option value="Banco Juno">Banco Juno</option>
                <option value="Gerencianet">Gerencianet</option>
                <option value="Banco do Brasil" class="hidden">Banco do Brasil</option>
                <option value="Carteira">Carteira</option>
              </select>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Tipo de cobrança
              <select type="text" class="form-control" id="tipocobranca" name="tipodecobranca" required>';
if (!empty($dd['tipodecobranca'])) {
  echo '<option value="' . $dd['tipodecobranca'] . '">' . $dd['tipodecobranca'] . '</option>';
}
echo '
                <option value="BOLETO">BOLETO</option>
                <option value="BOLETO_PIX">BOLETO_PIX</option>
                <option value="CARTEIRA">CARTEIRA</option>
              </select>
              </label>

              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Vencimento
                <input type="number" class="form-control" placeholder="Vencimento" name="vencimento" value="' .  +$dd['vencimento'] . '"/>
              </label> 
                             
              <div class="row"></div><hr>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Pessoa <small class="text-red">*obrigatório</small>
              <select type="text" class="form-control" name="tipo" id="tipo" required>
                <option value="' . $dd['tipo'] . '">' . $dd['tipo'] . '</option>
                <option value="Física">Fsica</option>
                <option value="Juridica">Juridica</option>
              </select>
              </label>
              <label class="col-lg-4 col-md-6 col-sm-12 col-xs-12">Nome
                <input type="text" class="form-control" placeholder="Nome" name="nome" value="' . $dd['nome'] . '" required/>
              </label>
              <div class="pessoafisica">
                <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">CPF
                  <input type="text" class="form-control cpf2" placeholder="Apenas números" name="cpf" value="' . $dd['cpf'] . '"/>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">RG
                  <input type="text" class="form-control" placeholder="Apenas números" name="rg" value="' . $dd['rg'] . '"/>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Estado RG
                <select type="text" class="form-control" name="rguf">
                <option value="' . @$dd['rguf'] . '">' . @$dd['rguf'] . '</option>';
foreach ($estadosbr as $item) {
  echo '<option value="' . $item . '">' . $item . '</option>';
}
echo '
                  </select>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Orgão emissor
                  <input type="text" class="form-control" placeholder="Orgão emissor" name="emissor" value="' . @$dd['emissor'] . '"/>
                </label>                
              </div>
              <div class="pessoajuridica">
                <label class="col-lg-4 col-md-6 col-sm-12 col-xs-12">Fantasia
                  <input type="text" class="form-control" placeholder="Fantasia" name="fantasia" value="' . $dd['fantasia'] . '"/>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">CNPJ
                  <input type="text" class="form-control cnpj" placeholder="Apenas números" name="cnpj" value="' . $dd['cnpj'] . '"/>
                </label>
                <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Incrição estadual
                  <input type="text" class="form-control" placeholder="Apenas números" name="ie" value="' . @$dd['ie'] . '"/>
                </label>
              </div>
                <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12"><b id="nascimento">Nascimento</b>
                  <input type="text" class="form-control data" placeholder="00-00-0000" name="nascimento" value="' . dataForm(@$dd['nascimento']) . '"/>
                </label>
              <div class="row"></div><hr>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Contato/whatsapp
                <input type="text" class="form-control celular" placeholder="Apenas números" name="contato" value="' . @$dd['contato'] . '"/>';
if ($dd['contato'] != '') {
  echo '
                    <a href="tel:' . $dd['contato'] . '" target="_blank"><i class="fa fa-phone text-black fa-2x"></i></a>&emsp;
                    <a href="https://api.whatsapp.com/send?phone=55' . @$dd['contato'] . '" target="_blank"><i class="fa fa-whatsapp text-green fa-2x"></i></a>';
}
echo '
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Contato 2
                <input type="text" class="form-control celular" placeholder="Apenas números" name="contato2" value="' . @$dd['contato2'] . '"/>';
if ($dd['contato2'] != '') {
  echo '
                    <a href="tel:' . @$dd['contato2'] . '" target="_blank"><i class="fa fa-phone text-black fa-2x"></i></a>&emsp;
                    <a href="https://api.whatsapp.com/send?phone=55' . @$dd['contato2'] . '" target="_blank"><i class="fa fa-whatsapp text-green fa-2x"></i></a>';
}
echo '
              </label>
              <label class="col-lg-4 col-md-6 col-sm-6 col-xs-12">E-mail
                <input type="text" class="form-control" placeholder="E-mail" name="email" value="' . @$dd['email'] . '"/>
              </label>  
              
             
              
               <div class="row"></div>
            <div class="pessoajuridica">
            <div class="row"></div><hr>            
              <label class="col-lg-4 col-md-6 col-sm-12 col-xs-12">Nome Resp.Legal
                  <input type="text" class="form-control" placeholder="Nome" name="nomeresponsavel" value="' . @$dd['nomeresponsavel'] . '"/>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">CPF Resp.Legal
                  <input type="text" class="form-control cpf2" placeholder="Apenas números" name="cpfresponsavel" value="' . @$dd['cpfresponsavel'] . '"/>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">RG Resp.Legal
                  <input type="text" class="form-control" placeholder="Apenas números" name="rgresponsavel" value="' . @$dd['rgresponsavel'] . '"/>
              </label>
              <label class="col-lg-2 col-md-6 col-sm-6 col-xs-12">Contato Resp.Legal
                  <input type="text" class="form-control celular" placeholder="Apenas números" name="contatoresponsavel" value="' . @$dd['contatoresponsavel'] . '"/>
              </label>
              <label class="col-lg-6 col-md-6 col-sm-6 col-xs-12">E-mail Resp.Legal
                  <input type="text" class="form-control" placeholder="E-mail" name="emailresponsavel" value="' . @$dd['emailresponsavel'] . '"/>
              </label>
            </div>

            <div class="row"></div><hr>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">CEP
              <input type="text" class="form-control cepBusca" placeholder="Apenas números" name="cep" value="' . $dd['cep'] . '"/>
            </label>
            <label class="col-lg-4 col-md-6 col-sm-4 col-xs-12">Rua/Alameda/Avenida/etc
              <input type="text" class="form-control enderecoBusca" placeholder="Rua/Alameda/Avenida/etc" name="rua" value="' . $dd['rua'] . '" required/>
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Número
              <input type="text" class="form-control" placeholder="Número" name="numero" value="' . $dd['numero'] . '"/>
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Bairro
              <input type="text" class="form-control bairroBusca" placeholder="Bairro" name="bairro" value="' . $dd['bairro'] . '" required/>
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Mnicipio
              <input type="text" class="form-control cidadeBusca" placeholder="Múnicipio" name="municipio" value="' . $dd['municipio'] . '" required/>
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Estado
              <input type="text" class="form-control ufBusca" placeholder="Estado" max="2" name="estado" value="' . $dd['estado'] . '" required/>
            </label>
             <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Código IBGE
              <input type="text" class="form-control ibgeBusca" placeholder="IBGE" name="ibge" value="' . $dd['ibge'] . '"/>
            </label>            
            <label class="col-lg-4 col-md-6 col-sm-4 col-xs-12">Complemento
              <input type="text" class="form-control" placeholder="Complemento" id="cpf" name="complemento" value="' . AspasForm($dd['complemento']) . '"/>
            </label>

            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Latitude
            <input type="text" class="form-control" placeholder="Latitude" name="latitude" value="' . $dd['latitude'] . '"/>
          </label>
          <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Longitude
            <input type="text" class="form-control" placeholder="Longitude" name="longitude" value="' . $dd['longitude'] . '"/>
          </label> 
          <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Link localização
          <input type="text" class="form-control" id="link2" value="https://painel.mkgestor.com.br/cad-cliente-localizacao.php?idcliente=' . $id . '&idempresa=' . $idempresa . '"/>
          </label>
          <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><center>
          <button type="button" class="btn btn-default" onclick="copiarTexto1()"><i class="fa fa-copy"></i> Copiar</button>

          </label></center>  
          <div class="row"></div><hr>
          <label class="col-lg-12">Obeservação
          <textarea rows="3" class="form-control" placeholder="Observação" name="obs">' . AspasForm(@$dd['obs']) . '</textarea>
        </label>
            
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      </form>
      
      
            <!--contrato-->
        <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
            <div class="col-lg-4">
              <h3 class="box-title">Contrato</h3>
              </div>
              <div class="col-lg-8">
              <button data-toggle="modal" data-target="#addContrato" class="btn btn-primary"><i class="fa fa-plus"></i> Cadastrar</button>
              <button onclick="altContrato(' . @$id . ')" class="btn btn-warning"><i class="fa fa-plus"></i> Alterar contrato</button>
            </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body"> 
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                  <thead>
                      <tr>
                          <th>N° contrato</th>
                          <th>Plano</th>
                          <th>Login</th>            
                           <th>Teste</th>   
                          <th>#</th>
                      </tr>
                  </thead>
                      <tbody id="tabContrato"></tbody>
				</table></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!--contrato-->
      
      
      <!-- histotico -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header">
            <div class="col-lg-4">
              <h3 class="box-title">Histórico</h3>
              </div>
              <div class="col-lg-8">
              <button data-toggle="modal" data-target="#addLog" class="btn btn-primary"><i class="fa fa-plus"></i> Cadastrar</button>
            </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="box-body table-responsive no-padding">
<table class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th style="width:30%">Descrição</th>
            <th>Usuário Cad</th>            
            <th>Data cad</th>
             <th>Usuário Alt</th>            
            <th>Data Alt</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody id="tabHistorico">
    
        </tbody>
</table></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- histotico -->
      

      
    </section>
    <!-- /.content -->    
</div>
<!-- /.content-wrapper -->

<!-- modal historio-->
<div class="modal" id="addLog" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar histrico</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAddLog" autocomplete="off">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
                    <input type="text" class="form-control hidden" name="idcliente" id="idcliente" value="' . @$id . '"/>
                    <label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrio
                        <textarea rows="3" class="form-control" placeholder="Descrião" name="obs"></textarea>
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
<!-- modal-->

<!-- modal alterarhistorio-->
<div class="modal" id="altLog" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar histrico</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAltLog">
      <div class="modal-body">
        	<div class="row" id="historicoRetorno">
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
<!-- modal-->


<!-- modal contrato-->
<div class="modal" id="addContrato" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAddContrato">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
                    <input type="text" class="form-control hidden" name="idcliente" value="' . @$id . '"/>
                    <label class="col-lg-12">Plano
                    <select type="text" class="form-control" name="plano" required>
                    <option value="">selecione</option>';
planosServidor($idempresa);
echo '
                    </select>
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
<!-- modal-->

<!-- modal alt contrato-->
<div class="modal" id="altContrato" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAlterarContrato">
      <div class="modal-body">
        	<div class="row" id="retornoAltContrato">
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
<!-- modal altContrato-->


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
      <form method="post" id="formCadastroChamado">
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

include('rodape.php'); ?>
<script>
  $('.clientes').addClass('active menu-open');

  $('#clientes').addClass('active');
  //formAtualizarCliente
  $('#formAtualizarCliente').submit(function() {
    $('#processando').modal('show');
    $.ajax({
      type: 'post',
      url: 'clientes-update.php',
      data: $('#formAtualizarCliente').serialize(),
      success: function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().fadeOut(3000).html(data);
        //window.setTimeout(function() { location.reload(); }, 3000);
      }
    });
    return false;
  });
  //tabContrato
  $().ready(function() {
    tabelaC();
  })

  function tabelaC() {
    var id = $('#id').val();
    $.get('clientes-contrato-tab.php', {
      id: id
    }, function(data) {
      $('#tabContrato').show().html(data);
    });
    return false;
  };
  //formAddContrato
  $('#formAddContrato').submit(function() {
    $('#addContrato').modal('hide');
    $('#processando').modal('show');
    $.ajax({
      type: 'post',
      url: 'clientes-contrato-update.php',
      data: $('#formAddContrato').serialize(),
      success: function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().fadeOut(3000).html(data);
        tabelaC();
      }
    });
    return false;
  });
  //tabhistorico
  $().ready(function() {
    tabelaH();
  })

  function tabelaH() {
    var id = $('#id').val();
    $.get('tab-historico.php', {
      id: id
    }, function(data) {
      $('#tabHistorico').show().html(data);
    });
    return false;
  };
  //insert histórico
  $('#formAddLog').submit(function() {
    $('#addLog').modal('hide');
    $.ajax({
      type: 'post',
      url: 'insert-historico.php',
      data: $('#formAddLog').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(9000).html(data);
        tabelaH();
        //window.setTimeout(function() { location.reload(); }, 3000);
      }
    });
    return false;
  });
  //tipo pessoa
  $(function($) {
    $('#tipo').on('change', function() {
      var valor = ($(this).val());
      if (valor == 'Física') {
        $('.pessoafisica').show();
        $('.pessoajuridica').hide().removeAttr('required', true);
        $('#nascimento').text('Nascimento');
      } else {
        $('.pessoafisica').hide().removeAttr('required', true);
        $('.pessoajuridica').show();
        $('#nascimento').text('Criação');
      }
    }).trigger('change');
  });
  //excluir
  function excluirHistorico(id) {
    var r = confirm("Press a button!");
    if (r == true) {
      $.get('historico-excluir.php', {
        id: id
      }, function(data) {
        $('#retorno').show().fadeOut(3000).html(data);
        tabelaH();
      });
    }
    return false;
  };
  //alterar
  function alterarHistorico(id) {
    $('#altLog').modal('show');
    $.get('historico-retorno.php', {
      id: id
    }, function(data) {
      $('#historicoRetorno').show().html(data);
    });
    return false;
  };
  //alterar historico
  $('#formAltLog').submit(function() {
    $('#altLog').modal('hide');
    $.ajax({
      type: 'post',
      url: 'historico-alterar.php',
      data: $('#formAltLog').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(3000).html(data);
        tabelaH();
        //window.setTimeout(function() { location.reload(); }, 3000);
      }
    });
    return false;
  });
  //
  //testar conexão
  function testarConexao(id) {
    $('#processando').modal('show');
    $.get('clientes-testar-conexao.php', {
      id: id
    }, function(data) {
      $('#processando').modal('hide');
      $('#retorno').show().html(data);
    });
    return false;
  }
  //excluir contrato
  function excluirContrato(id) {
    let r = confirm("Deseja excluir?");
    if (r == true) {
      $('#processando').modal('show');
      $.get('clientes-excluir-contrato.php', {
        id: id
      }, function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().fadeOut(2500).html(data);
        tabelaC();
      });
      return false;
    };
  };
  ///

  function copiarTexto1() {
    var tt = document.getElementById('link2');
    tt.select();
    document.execCommand("Copy");
  }

  //abrir chamado
  function abrirChamado(id) {
    $('#CadastrarChamado').modal('show');
    $.get('clientes-chamado-retorno.php', {
      id: id
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
        $('#retorno').show().fadeOut(3000).html(data);
      }
    });
    return false;
  });
  //retornoAltContrato
  function altContrato(id) {
    $('#altContrato').modal('show');
    $.get('clientes-retorno-alterar-contrato.php', {
      id: id
    }, function(data) {
      $('#retornoAltContrato').show().html(data);
    })
    return false;
  }
  //salvar chamado
  $('#formAlterarContrato').submit(function() {
    $('#altContrato').modal('hide');
    $('#processando').modal('show');
    $.ajax({
      type: 'post',
      url: 'clientes-alterar-contrato-update.php',
      data: $('#formAlterarContrato').serialize(),
      success: function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().fadeOut(3000).html(data);
      }
    });
    return false;
  });
</script>