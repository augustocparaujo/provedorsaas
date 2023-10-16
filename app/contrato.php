<?php
include('topo.php');
$query = mysqli_query($conexao, "SELECT * FROM contratos WHERE idcliente='$iduser' AND idempresa='$idempresa'") or die(mysqli_error($conexao));
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
            <h3 class="box-title">Contrato(s)</h3>
          </div>
          <div class="box-body no-padding">
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Contrato</th>
                    <th>Plano</th>
                    <th>Ativação</th>
                    <th>Situação</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (mysqli_num_rows($query) >= 1) {
                    while ($dd = mysqli_fetch_array($query)) {
                      echo '
<tr>
<td>' . $dd['id'] . '</td>
    <td>' . $dd['nomeplano'] . '</td>
    <td>' . dataForm($dd['ativacao']) . '</td>
    <td>' . $dd['situacao'] . '</td>
</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="3">Sem cobranças</td></tr>';
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
<!-- /.content-wrapper -->
<?php include('rodape.php'); ?>