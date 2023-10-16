<?php 
include('topo.php');

@$id = $_GET['id'];
@$nomecliente = $_GET['nomecliente'];
$query = mysqli_query($conexao,"SELECT * FROM notas WHERE notas.idcliente='$id'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
echo'
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
    <div class="row">
    <center><h4 class="box-title">Cliente: '.@$id.' - '.@$nomecliente.'</h4></center>
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            <center>
                   <button type="button" class="btn btn-warning" title="upload nota" data-toggle="modal" data-target="#addnota"><i class="fa fa-upload"></i> Inserir nota</button>
                  <a href="clientes-exibir.php?id='.$id.'" class="btn btn-info" style="margin:2px"><i class="fa fa-file-text-o"></i> Dados cliente</a>
                  <a href="clientes-financeiro-exibir.php?id='.$id.'" class="btn btn-primary"><i class="fa fa-dollar"></i> Financeiro</a>
                  </center>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                    <tr>
                    <th>Descrição</th>
                    <th>Vencimento</th>
                    <th>Nota</th>
                    <th>#</th>
                    </tr>
                </tbody>
                <tfoot id="tabela">
                </tfoot>
              </table>
            </div>
            <br>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal" id="addnota" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h5 class="modal-title">Selecionar nota</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formNota" enctype="multipart/form-data" autocomplete="off">
      <div class="modal-body">
        	<div class="row">
            <input type="text" class="hidden" id="id" name="idcliente" value="'.$id.'"/>
            <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Número nota
            <input type="number" class="form-control" name="numeronota" required/>
          </label>
           <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Descrição
            <input type="text" class="form-control" name="descricao" placeholder="Descrição" required/>
          </label>
           <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Vencimento
            <input type="text" class="form-control data" name="vencimento" required/>
          </label>
          <label class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Nota (.pdf)
            <input type="file" class="form-control" name="arquivo" accept="application/pdf", required/>
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
</div>';

include('rodape.php'); ?>
<script>
    $('#clientes').addClass('active');
      //tabFinCli
    $().ready(function(){ tabelaFin(); })
    function tabelaFin(){
      var id = $('#id').val();
      $.get('tab-notas-cliente.php',{id:id},function(data){
        $('#tabela').show().html(data);
      });
    return false;
    };

   //enviar nota
    $('#formNota').submit(function() {
      $('#addnota').modal('hide');
      var formData = new FormData(this);
      $.ajax({
          type: 'POST',
          url: 'insert-nota-cliente.php',
          data: formData,
          success: function(data) {
            $('#retorno').show().fadeOut(6000).html(data);
            $('#formNota').each(function(){this.reset();});
            tabelaFin();
          },
          cache: false,
          contentType: false,
          processData: false,
      });
      return false;
    });
    //excluir nota
    function excluirNota(id){
      $.get('excluir-nota.php',{id:id},function(data){
        $('#retorno').show().html(data);
        tabelaFin();
      });
      return false;
    }
</script>