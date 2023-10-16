<?php
include('topo.php');
$query = mysqli_query($conexao, "SELECT * FROM cobranca WHERE idcliente='$iduser' AND idempresa='$idempresa'") or die(mysqli_error($conexao));
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <!-- linha 1-->
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h3><i class="fa fa-user"></i> <?php echo 'Seja bem vindo(a):<br /><i class="text-primary"> ' . $_SESSION['usuario']; ?></i></h3>
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Financeiro</h3>
          </div>
          <div class="box-body no-padding">
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Venc.</th>
                    <th>Valor</th>
                    <th>Situação</th>
                    <th>#</th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  if (mysqli_num_rows($query) > 0) {
                    while ($dd = mysqli_fetch_array($query)) {
                      echo '
<tr>
    <td>' . date('d-m-Y', strtotime($dd['vencimento'])) . '</td>
    <td>R$ ' . Real($dd['valor']) . '</td>
    <td>' . situacao($dd['situacao']) . '</td>
    <td>';
                      if ($dd['banco'] == 'Banco Juno' or $dd['banco'] == 'Gerencianet') {
                        if ($dd['nparcela'] >= 2) {
                          echo '<a href="' . $dd['installmentLink'] . '" target="_blank" title="Imprimir boleto"><i class="fa fa-credit-card text-black fa-2x"></i></a>&ensp; ';
                        }
                        echo '
              <a href="' . $dd['link'] . '" target="_blank" title="Imprimir boleto"><i class="fa fa-barcode text-black fa-2x"></i></a>&ensp;';
                        if ($dd['banco'] == 'Gerencianet') {
                          echo '<a href="#" onclick="pix(' . $dd['id'] . ')" title="Cópiar código pix"><i class="fa fa-chain fa-2x text-black"></i></a> ';
                        }
                      }
                      echo '
    </td>
</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="5">Sem cobranças</td></tr>';
                  }

                  ?>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>

<!-- modal exibir recebimento-->
<div class="modal" id="modalTeste" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
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


<!-- /.content-wrapper -->
<?php include('rodape.php'); ?>

<script>
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
</script>