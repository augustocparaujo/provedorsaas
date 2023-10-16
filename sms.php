<?php
include('topo.php');
include('api_smsnet.php');
@$totalsms = saldoSms();

$s = mysqli_query($conexao, "SELECT contato FROM cliente WHERE idempresa='$idempresa'");
if (mysqli_num_rows($s) > 0) {
    $totalcontato = mysqli_num_rows($s);
}
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

              <h3 class="box-title">SMS - Saldo: ' . @$totalsms . '</h3>
              <p class="text-red">
              OBS: Para enviar sms ou whatsapp precisar ter contratado API da antes (sms=0,01, what=0,06) /
              Habiliar sms ou whatsapp e configurar</p>            
              <div class="box-tools pull-right">
                <div class="btn-group" id="realtime" data-toggle="btn-toggle">
                </div>
              </div>
            </div>
            <div class="box-body">
            <form id="formSms">            
                <label class="col-lg-4  col-sm-12">Tipo
                    <select class="form-control" name="tipo" id="tipo" required>
                        <option value="Manual">Manual</option>
                        <option value="Todos">Todos</option>
                        <option value="Vencido" disabled>Todos vencidos(em desenvolvimento)</option>
                    </select>
                </label>
               
                <label class="col-lg-4  col-sm-12 todos" style="display:none">Todos
                    <input type="text" class="form-control" name="todos" value="' . @$totalcontato . ' clientes" readonly/>
                </label>
           
                <label class="col-lg-4  col-sm-12 manual" style="display:none">Digite o telefone
                    <input type="number" class="form-control manual" placeholder="Número" name="contato"/>
                </label>
               
                <label class="col-lg-12  col-sm-12">Texto
                <textarea rows="6" class="form-control" placeholder="Texto" name="msg" required></textarea>
                </label>
                <div class="row"></div><br> 
                <h5><code>* Lembrando que o máximo de caracter que um sms recebe é 160 por mensagem</code></h5>
                
                <div class="row"></div><br> 

                    <center>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </center>
                </div>                             
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
    $('#sms').addClass('active');
    //tipo
    $(function($) {
        $('#tipo').on('change', function() {
            var valor = $(this).val();
            if (valor == 'Todos') {
                $('.todos').show();
                $('.manual').hide().removeAttr('required', false);

            } else {
                $('.manual').show();
                $('.todos').hide().removeAttr('required', false);
            }
        }).trigger('change');
    });
    //formSms
    $('#formSms').submit(function() {
        $('#processando').modal('show');
        $.ajax({
            type: 'post',
            url: 'sms-envia-manual.php',
            data: $('#formSms').serialize(),
            success: function(data) {
                $('#processando').modal('hide');
                $('#retorno').show().fadeOut(10000).html(data);
            }
        });
        return false;
    });
    //formSmsAutomatico
    $('#formSmsAutomatico').submit(function() {
        $('#processando').modal('show');
        $.ajax({
            type: 'post',
            url: 'sms-automatico.php',
            data: $('#formSmsAutomatico').serialize(),
            success: function(data) {
                $('#processando').modal('hide');
                $('#retorno').show().fadeOut(10000).html(data);
            }
        });
        return false;
    });
</script>