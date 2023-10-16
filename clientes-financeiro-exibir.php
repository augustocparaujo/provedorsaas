<?php
include('topo.php');

@$id = $_GET['id'];
$query = mysqli_query($conexao, "SELECT * FROM cobranca WHERE idcliente='$id'") or die(mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
//tipo cobrança
$query0 = mysqli_query($conexao, "SELECT * FROM cliente WHERE id='$id'") or die(mysqli_error($conexao));
$ddc = mysqli_fetch_array($query0);
$nomecliente = $ddc['nome'];
if ($ddc['cpf'] != '') {
  $doccliente = $ddc['cpf'];
} elseif ($ddc['cnpj'] != '') {
  $doccliente = $ddc['cnpj'];
} else {
  $doccliente = '';
}
$tipocobranca = [$ddc['tipodecobranca']];

$buscac = mysqli_query($conexao, "SELECT contratos.id, contratos.plano, plano.plano AS planonome, plano.valor FROM contratos
INNER JOIN plano ON contratos.plano = plano.id
WHERE contratos.idcliente='$id' AND contratos.situacao <> 'Cancelado'") or die(mysqli_error($conexao));
while ($retC = mysqli_fetch_array($buscac)) {
  @$totalplano = @$totalplano + $retC['valor'];

  if (@$contrato != @$retC['id']) {
    @$contratos = @$contratos . ' ' . $retC['id'] . '/' . @$retC['planonome'];
  }
  @$contrato = $retC['id'];
}
echo '
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
            <h3 class="box-title">Cliente: ' . @$id . ' - ' . @$ddc['nome'] . ' | ';
if ($ddc['cpf'] != '') {
  echo 'CPF: ' . @$ddc['cpf'];
} else {
  echo 'CNPJ: ' . @$ddc['cnpj'];
}
echo '</h3>
            </div>
            </div>
            <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
            <center>';
if (@$ddc['tipodecobranca'] != 'Porta porta') {
  echo '        
                    <a href="#" data-toggle="modal" data-target="#modalGerarBoleto" class="btn btn-primary" style="margin:2px"><i class="fa fa-barcode"></i> Gerar cobrança</a>
                    <a href="#" data-toggle="modal" data-target="#modalGerarCarne" class="btn btn-warning hidden" style="margin:2px"><i class="fa fa-barcode"></i> Carnê</a>';
}
echo '
                  <a href="#" data-toggle="modal" data-target="#CobrancaSemBoleto" class="btn bg-purple hidden" style="margin:2px"><i class="fa fa-barcode"></i> Cobrança sem boleto</a>
                  <a href="clientes-exibir.php?id=' . $id . '" class="btn btn-info" style="margin:2px"><i class="fa fa-file-text-o"></i> Dados cliente</a>
                  <a href="clientes-financeiro-exibir.php?id=' . $id . '" class="btn btn-warning"><i class="fa fa-exclamation"></i> Pendente</a>
                  <a class="btn btn-success" onclick="recebido(' . $id . ')"><i class="fa fa-check-square-o"></i> Pagas</a>
                  <a class="btn btn-black" onclick="cancelado(' . $id . ')"><i class="fa fa-remove"></i> Canceladas</a>
                  </center>
            </div>
            </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                    <tr>
                    <th>Nº</th>
                    <th>Banco</th>
                    <th>Data Venc.</th>
                    <th>Valor</th>
                    <th>Data Pag.</th>
                    <th>Situação</th>
                    <th>#</th>
                    </tr>
                </thead>
                <tbody id="tabela">
            </tbody>
                <tfoot>
                </tfoot>
              </table>
              <br /><br /><br /><br /><br />
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

<div class="modal" id="modalGerarBoleto" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cobrana com Boleto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formGerarCobranca">
      <div class="modal-body">
      <div class="row"></div><hr>
        	<div class="row">
            <input type="text" class="hidden" id="id" name="id" value="' . $id . '"/>
            <input type="text" class="hidden" name="idempresa" value="' . $idempresa . '"/>
              <label class="col-md-4">Banco
              <select type="text" class="form-control" id="banco" name="banco" required>';
if ($ddc['banco'] != '') {
  echo '<option value="' . $ddc['banco'] . '">' . $ddc['banco'] . '</option>';
} else {
  echo '<option value="">selecione</option>';
}
echo '
                  <option value="Banco Juno">Banco Juno</option>
                  <option value="Gerencianet">Gerencianet</option>
                  <option value="Carteira">Carteira</option>
              </select>
              </label>
              <label class="col-md-4">Parcela
                <input type="number" class="form-control" placeholder="Parcela" name="nparcela" value="1">
              </label>
              <label class="col-md-4">Vencimento <i class="text-red">*</i>
                <input type="text" class="form-control data" placeholder="Vencimento" name="vencimento" value="' . date(@$ddc['vencimento'] . '-m-Y') . '" required/>
              </label>
              <label class="col-md-4">Valor da parcela <i class="text-red">*</i>
                <input type="text" class="form-control real" name="valor" value="' . Real(@$totalplano) . '"/>
              </label>
              <label class="col-xs-12">Descrição
                <textarea rows="4" class="form-control" name="obs" required>FATURA REFENTE A CONTRATO(S):' . $contratos . '</textarea>
              </label>
        	</div>       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Gerar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal" id="modalGerarCarne" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h5 class="modal-title">Cobrança com Carnê</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCobrancaCarne">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
            <input type="text" class="hidden" name="id" value="' . $id . '"/>
            <input type="text" class="hidden" name="banco" value="Sem-boleto"/>
              <label class="col-xs-12">Nº de parcelas <i class="text-red">*</i>
                <input type="number" class="form-control data" placeholder="N parcelas" name="nparcela" required/>
              </label>
              <label class="col-xs-12">Data vencimento <i class="text-red">*</i>
                <input type="text" class="form-control data" placeholder="Vencimento" name="vencimento" value="' . date(@$ddc['vencimento'] . '-m-Y') . '" required/>
              </label>
              <label class="col-xs-12">Valor da parcela <i class="text-red">*</i>
                <input type="text" class="form-control real" name="valormanual" value="' . Real(@$totalplano) . '"/>
              </label>
              <label class="col-xs-12">Valor desconto
                <input type="text" class="form-control real" name="descontomanual" placeholder="0,00"/>
              </label>
              <label class="col-xs-12">Valor multa
                <input type="text" class="form-control real" name="multamanual" placeholder="0,00"/>
              </label>
              <label class="col-xs-12">Dias para desconto
                <input type="number" class="form-control" name="diasdescontomanual"/>
              </label>
              <label class="col-xs-12">Descrião
                <input type="text" class="form-control" name="descricaomanual"/>
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

<div class="modal" id="CobrancaSemBoleto" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h5 class="modal-title">Cobrança sem boleto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCobrancaSemBoleto">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
                  <input type="text" class="hidden" name="id" value="' . $id . '"/>
            <input type="text" class="hidden" name="banco" value="Sem-boleto"/>
                <label class="col-lg-12">Tipo
                <select type="text" class="form-control" name="tipocobranca" required>
                  <option value="plano">Plano</option>
                  <option value="servico">Serviço</option>
                </select>
              </label>
              <label class="col-xs-12">Nº de parcelas <i class="text-red">*</i>
                <input type="number" class="form-control data" placeholder="Nº parcelas" name="nparcela" required/>
              </label>
		        	<label class="col-lg-12">Vencimento
			        	<input type="text" class="form-control data" placeholder="00-00-0000" name="vencimento" value="' . date(@$ddc['vencimento'] . '-m-Y') . '" required/>
		        	</label>
              <label class="col-lg-12">Valor
			        	<input type="text" class="form-control real" name="valor" value="' . @Real(@$totalplano) . '" required/>
		        	</label>
              <label class="col-xs-12">Descrição
                <input type="text" class="form-control" name="obs"/>
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

<div class="modal" id="receberCobranca" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h5 class="modal-title">Receber</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formReceberCobranca">
      <div class="modal-body">
        	<div class="row" id="callbackReceber"></div>       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal" id="cancelarCobranca" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h5 class="modal-title">Cancelar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCancelarCobranca">
      <div class="modal-body">
        	<div class="row">
            <input type="hidden" name="id" id="idcancelamento"/>
            <label class="col-md-12">
              <textarea rows="" class="form-control" placeholder="Justifique o cancelamento" name="obs" required></textarea>
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

<!-- modal exibir recebimento-->
<div class="modal" id="modalTeste" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Exibir</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="row" id="retornoRR">
                
            </div>       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<!-- modal exibir recebimento--> 

';

include('rodape.php'); ?>
<script>
  $('.clientes').addClass('active menu-open');

  $('#clientes').addClass('active');
  //tabFinCli
  function tabelaFin() {
    var id = $('#id').val();
    $.get('tab-financeiro-cliente.php', {
      id: id
    }, function(data) {
      $('#tabela').show().html(data);
    });
    return false;
  };
  tabelaFin();
  //cancelados
  function cancelado(id) {
    $.get('tab-financeiro-cancelado-cliente.php', {
      id: id
    }, function(data) {
      $('#tabela').show().html(data);
      //tabelaFin();
    });
    return false;
  }
  //RECEBIDO
  function recebido(id) {
    $.get('tab-financeiro-recebido-cliente.php', {
      id: id
    }, function(data) {
      $('#tabela').show().html(data);
      //tabelaFin();
    });
    return false;
  }

  //gerar próximo boleto
  $('#formGerarCobranca').submit(function() {
    $('#modalGerarBoleto').modal('hide');
    $('#processando').modal('show');
    $.ajax({
      type: 'post',
      url: 'cobranca-gerar.php',
      data: $('#formGerarCobranca').serialize(),
      success: function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().html(data);
        tabelaFin();
      }
    });
    return false;
  });
  //consultar
  function consultarCobranca(id) {
    $('#processando').modal('show');
    $.get('cobranca-consultar.php', {
      id: id
    }, function(data) {
      $('#processando').modal('hide');
      $('#retorno').show().html(data);
      tabelaFin();
    });
    return false;
  }
  //cancelar cobrança
  function cancelarCobranca(id) {
    $("#idcancelamento").val(id);
    $('#cancelarCobranca').modal('show');
  }
  ///cancela
  $('#formCancelarCobranca').submit(function() {
    $('#cancelarCobranca').modal('hide');
    $('#processando').modal('show');
    $.post({
      type: 'post',
      url: 'cobranca-cancelar.php',
      data: $('#formCancelarCobranca').serialize(),
      success: function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().html(data);
        tabelaFin();
      }
    });
    return false;
  });

  //receber
  function receberCobranca(id) {
    $('#receberCobranca').modal('show');
    $.get('cobranca-receber-retorno.php', {
      id: id
    }, function(data) {
      $('#callbackReceber').show().html(data);
    });
    return false;
  }
  //update receber
  $('#formReceberCobranca').submit(function() {
    $('#processando').modal('show');
    $('#receberCobranca').modal('hide');
    $.post({
      type: 'post',
      url: 'cobranca-receber.php',
      data: $('#formReceberCobranca').serialize(),
      success: function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().html(data);
        tabelaFin();
      }
    });
    return false;
  });

  function exibirRecebido(titulo) {
    $('#modalTeste').modal('show');
    $.get('exibir-caixa.php', {
      titulo: titulo
    }, function(data) {
      $('#retornoRR').show().html(data);
    });
    return false;
  }

  //estornarCobranca
  function estornarCobranca(id) {
    var r = confirm("Deseja estornar?");
    if (r == true) {
      $('#processando').modal('show');
      $.get('cobranca-estornar.php', {
        id: id
      }, function(data) {
        $('#processando').modal('hide');
        $('#retorno').show().html(data);
        tabelaFin();
      });
    }
    return false;
  }

  //copia cídogo pix
  function pix(id) {
    $('#modalTeste').modal('show');
    $.get('exibir-pix.php', {
      id: id
    }, function(data) {
      $('#retornoRR').show().html(data);
    });
    return false;
  }

  function copiarPix() {
    var tt = document.getElementById('codigoPix');
    tt.select();
    document.execCommand("Copy");
    alert('Código copiado');
  }

  function enviaBoleto(id) {
    $('#processando').modal('show');
    $.get('notificacao-cobranca-manual-envia.php', {
      id: id
    }, function(data) {
      $('#processando').modal('hide');
      $('#retorno').show().fadeOut(2500).html(data);
    });
    return false;
  }
</script>