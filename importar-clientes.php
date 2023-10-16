<?php
include('topo.php');
echo '
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
              <h3 class="box-title">Importar arquivo (* sempre em .csv seguindo o padrão da tabela exemplo e separado por ",")</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-primary" data-toggle="modal" data-target="#importar"><i class="fa fa-plus"></i> Importar</button>
                <a class="btn btn-info" href="tabela.xlsx" download><i class="fa fa-download"></i> Exemplo</a>';
if (file_exists("tabelas/" . @$idempresa . '.csv')) {
  echo '
                <a class="btn bg-navy" href="#" onclick="injetar()"><i class="fa fa-database text-red"></i> Injetar no banco</a>';
}

echo '
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            
            <div class="table-responsive no-padding">
            <table class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>N</th>
                        <th>Empresa</th>
                        <th>Tipo</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>CNPJ</th>
                        <th>Fantasia</th>
                        <th>IE</th>
                        <th>RG</th>
                        <th>Nascimento</th>
                        <th>Contato</th>
                        <th>Email</th>
                        <th>CEP</th>
                        <th>Rua</th>
                        <th>Numero</th>
                        <th>Bairro</th>
                        <th>Municipio</th>
                        <th>UF</th>
                        <th>Complemento</th>
                        <th>Ativação</th>
                        <th>Vencimento</th>
                        <th>Situação</th>
                        <th>Usuário/Cad</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>';
if (file_exists("tabelas/" . @$idempresa . '.csv')) {
  if (($open = fopen("tabelas/" . @$idempresa . '.csv', "r")) !== FALSE) {
    $row = 0;
    while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
      $array[] = $data;
      if ($row > 1) {
        echo '
        <tr>
          <td>' . $row . '</td>
          <td>' . @$idempresa . '</td>
          <td>' . $data[0] . '</td>
          <td>' . $data[1] . ' </td>
          <td>' . $data[2] . '</td>
          <td>' . $data[3] . '</td>
          <td>' . $data[4] . '</td>
          <td>' . $data[5] . '</td>
          <td>' . $data[6] . '</td>
          <td>' . $data[7] . '</td>
          <td>' . $data[8] . '</td>
          <td>' . $data[9] . '</td>
          <td>' . $data[10] . '</td>
          <td>' . $data[11] . '</td>
          <td>' . $data[12] . '</td>
          <td>' . $data[13] . '</td>
          <td>' . $data[14] . '</td>
          <td>' . $data[15] . '</td>
          <td>' . $data[16] . '</td>
          <td>' . $data[17] . '</td>
          <td>' . @$data[18] . '</td>
          <td>ativo</td>
          <td>' . $nomeuser . '</td>
          <td>' . date('Y-m-d') . '</td> 
        </tr>';
      }
      $row++;
    }
    fclose($open);
  }
} else {
  echo '<tr><td colspan="5">sem arquivo</td></tr>';
}

echo '
            </tbody>
            </table>
            </div>
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


<!-- modal-->
<div class="modal" id="importar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="form" enctype="multipart/form-data">
      <div class="modal-body">
        	
            <div class="form-group">
            <label for="exampleInputFile">Arquivo CSV</label>
            <input type="file" id="exampleInputFile" name="file" accept=".csv">
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Enviar arquivo</button>
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
  $('#importar-clientes').addClass('active');

  $('#form').submit(function() {
    $('#processando').modal('show');
    $('#importar').modal('hide');
    var formData = new FormData(this);
    $.ajax({
      type: 'POST',
      url: 'receber-arquivo-csv.php',
      data: formData,
      success: function(data) {
        $('#retorno').show().fadeOut(2500).html(data);
        window.setTimeout(function() {
          history.go();
        }, 2501);
      },
      cache: false,
      contentType: false,
      processData: false,
    });
    return false;
  });
  //import
  function injetar() {
    $('#processando').modal('show');
    $.get('injetar-no-banco.php', function(data) {
      $('#processando').modal('hide');
      $('#retorno').show().html(data);
    });
    return false;
  }
</script>