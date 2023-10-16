<?php
include('topo.php');

$s = mysqli_query($conexao, "SELECT contato FROM cliente WHERE contato <> '' AND idempresa='$idempresa'");
$totalcontato = mysqli_num_rows($s);
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content" style="font-size:75%; !important; ">
      <div class="row">
      <div class="col-xs-12">
          <!-- interactive chart -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <i class="fa fa-commenting"></i>
              <h3 class="box-title">Notificação manual</h3>           
              <div class="box-tools pull-right">
                <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                </div>
              </div>
            </div>
            <div class="box-body">
                <form id="formSms" enctype="multipart/form-data">          
                    <label class="col-lg-4 col-sm-12 col-xs-12">Tipo
                        <select class="form-control" name="tipo" id="tipo" required>
                            <option value="Manual">MANUAL</option>
                            <option value="Todos">TODOS</option>
                            <option value="VENCIDO" disabled>TODOS OS VENCIDO(em desenvolvimento)</option>
                        </select>
                    </label>
                
                    <label class="col-lg-4 col-sm-12 col-xs-12 todos" style="display:none">Todos
                        <input type="text" class="form-control" name="todos" value="' . @$totalcontato . ' clientes" readonly/>
                    </label>

                    <label class="col-lg-4 col-sm-12 col-xs-12 todos" style="display:none">Data
                        <input type="date" class="form-control" name="data"/>
                    </label>

                    <label class="col-lg-4 col-sm-12 col-xs-12 manual" style="display:none">Nome
                        <input type="text" class="form-control manual" placeholder="Nome" name="nome"/>
                    </label>
            
                    <label class="col-lg-4 col-sm-12 col-xs-12 manual" style="display:none">Digite o telefone
                        <input type="number" class="form-control manual" placeholder="Número" name="contato"/>
                    </label>
                
                    <label class="col-lg-12 col-sm-12 col-xs-12">Texto
                    <textarea rows="10" class="form-control" placeholder="Texto" name="msg" required></textarea>
                    </label>  
                    
                    <div class="row"></div><br> 

                        <center>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </center>                            
                </form> 
        <!-- /.box-body-->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';
include('rodape.php'); ?>
<script>
    $('.notificacoes').addClass('active menu-open');
    $('#notificacao-envia-manual').addClass('active');
    //tipo
    $(function($) {
        $('#tipo').on('change', function() {
            var valor = $(this).val();
            if (valor == 'Todos') {
                $('.todos').show().attr('required', true);;
                $('.manual').hide().removeAttr('required', false);

            } else {
                $('.manual').show().attr('required', true);;;
                $('.todos').hide().removeAttr('required', false);
            }
        }).trigger('change');
    });
    //formSms
    $('#formSms').submit(function() {
        $('#processando').modal('show');
        var formData = new FormData(this);
        $.ajax({
            type: 'post',
            url: 'notificacao-manual-envia.php',
            data: formData,
            success: function(data) {
                $('#processando').modal('hide');
                $('#retorno').show().fadeOut(10000).html(data);
            },
            cache: false,
            contentType: false,
            processData: false,
        });
        return false;
    });
</script>