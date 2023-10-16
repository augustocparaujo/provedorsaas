<?php
include('topo.php');
$id = $_GET['idcliente'];
$query = mysqli_query($conexao, "SELECT user.*, dadoscobranca.chavepixaleatoria FROM user 
LEFT JOIN dadoscobranca ON dadoscobranca.idempresa = user.idempresa
WHERE user.id='$id' AND dadoscobranca.recebercom='Gerencianet'") or die(mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
        <div class="col-lg-12 col-xs-12">
          <!-- Custom Tabs (Pulled to the right) -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class="active"><a href="#">Financeiro</a></li>             
                <li><a href="perfil-usuario-isp.php?id=' . $id . '">Dados IPS</a></li>
                <li class="pull-left header"><i class="fa fa-th"></i> Cliente ISP</li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active">
              <div class="box-header">
                <center>     
                    <a href="#" data-toggle="modal" data-target="#modalGerarBoleto" class="btn btn-primary" style="margin:2px"><i class="fa fa-barcode"></i> Boleto ou Carteira</a>                   
                </center>
              </div>

                <div class="box-body panel box box-primary table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Nº</th>
                                <th>Valor</th>
                                <th>Data Venc.</th>
                                <th>Data Pag.</th>
                                <th>Dias/Atraso</th>
                                <th>Situação</th>
                                <th>#</th>
                            </tr>
                        </tbody>
                        <tfoot id="tabela"></tfoot>
                    </table>
                </div>
              </div>
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';

echo '
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
        	<div class="row">
            <input type="text" class="hidden" id="id" name="id" value="' . @$id . '"/>
              <label class="col-md-4">Tipo
                <select type="text" class="form-control" placeholder="Tipo" name="tipo">
                  <option value="Boleto">Boleto</option>
                  <option value="Carteira">Carteira</option>
                </select>
              </label>  
              <label class="col-md-4">Parcela
                <input type="number" class="form-control" placeholder="Parcela" name="nparcela" value="1">
              </label>
              <label class="col-md-4">Vencimento <i class="text-red">*</i>
                <input type="text" class="form-control data" placeholder="Vencimento" name="vencimento" value="' . date('d-m-Y') . '" required/>
              </label>
              <label class="col-md-4">Valor da parcela <i class="text-red">*</i>
                <input type="text" class="form-control real" name="valor" value="' . Real(@$totalplano) . '"/>
              </label>
              <label class="col-xs-12">Descrição
                <textarea rows="5" class="form-control" name="obs" required>FATURA REFENTE A SERVIÇO DE USO DE SOFTWARE</textarea>
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
';

include('rodape.php');
?>
<script>
    $('.gestao').addClass('active menu-open');
    $('#clientes2').addClass('active');

    //tabela
    $().ready(function() {
        tabela();
    })

    function tabela() {
        var id = <?php echo $id; ?>;
        $.get('tab-financeiro-cliente-isp.php', {
            id: id
        }, function(data) {
            $('#tabela').html(data);
        });
        return false;
    };

    //gerar próximo boleto
    $('#formGerarCobranca').submit(function() {
        $('#modalGerarBoleto').modal('hide');
        $('#processando').modal('show');
        $.ajax({
            type: 'post',
            url: 'cobranca-gerar-isp.php',
            data: $('#formGerarCobranca').serialize(),
            success: function(data) {
                $('#processando').modal('hide');
                $('#retorno').show().html(data);
                tabela();
            }
        });
        return false;
    });

      //consultar
  function consultarCobrancaIsp(id) {
    $('#processando').modal('show');
    $.get('cobranca-consultar-isp.php', {
      id: id
    }, function(data) {
      $('#processando').modal('hide');
      $('#retorno').show().html(data);
      tabelaFin();
    });
    return false;
  }
  //cancelar cobrança
  function cancelarCobrancaIsp(id) {
    $('#processando').modal('show');
    $.get('cobranca-cancelar-isp.php',{id:id},function(data) {
      $('#processando').modal('hide');
        $('#retorno').show().html(data);
        tabela();
    });
    return false;
  }
    //cancelar cobrança
    function cancelarCobrancaIsp2(id) {
    $('#processando').modal('show');
    $.get('cobranca-cancelar-isp2.php',{id:id},function(data) {
      $('#processando').modal('hide');
        $('#retorno').show().html(data);
        tabela();
    });
    return false;
  }


  //receber
  function receberCobrancaIsp(id) {
    $('#processando').modal('show');
    $.get('cobranca-receber-isp.php', {
      id: id
    }, function(data) {
      $('#processando').modal('hide');
        $('#retorno').show().html(data);
        tabela();
    });
    return false;
  }
</script>