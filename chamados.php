<?php 
include('topo.php');
echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">   
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
            <div class="col-lg-4">
              <h3 class="box-title">Chamados</h3>
              </div>
              <div class="col-lg-8">
              	<button class="btn btn-primary hidden" data-toggle="modal" data-target="#CadastrarChamado"><i class="fa fa-plus"></i> Cadastrar chamado</button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="tabela"></div>
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

<!-- modal abrir chamado-->
<div class="modal" id="abrirChamado" tabindex="-1" role="dialog">
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
        	<div class="row">
        		<div class="col-lg-12">
                    <input type="text" class="form-control hidden" name="idcliente" id="idcliente" value="'.@$idcliente.'"/>
                    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Tipo
                    <select type="text" class="form-control" name="tipo" required>
                        <option vaue="INCIDENTE">INCIDENTE</option>
                        <option value="INSTALAÇÃO">INSTALAÇÃO</option>
                        <option value="SOLICITAÇÃO">SOLICITAÇÃO</option>
                    </select>
                    </label>
                    <label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrição
                        <textarea rows="3" class="form-control" placeholder="Descrição" name="obs"></textarea>
                    </label>
                    <div class="row"></div>
                    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Data para atendimento
                      <input type="date" class="form-control" name="dataatendimento" required/>
                    </label>
                    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Situação
                    <select type="text" class="form-control" name="situacao" required>
                        <option vaue="ABERTA">ABERTA</option>
                        <option value="SOLUCIONADO">SOLUCIONADO</option>
                        <option value="PENDENTE">PENDENTE</option>
                        <option value="PENDENTE TERCEIRO">PENDENTE TERCEIRO</option>
                        <option value="CANCELADO">CANCELADO</option>
                    </select>
                    </label>
                    <div class="row"></div><hr>
                    <ol style="font-size:12px">
                        <li>INCIDENTE: Problema em equipamento, rompimento de cabo, etc</li>
                        <li>INSTALAÇÃO: Agendamento de instalação de equipamento, assinatura, etc</li>
                        <li>SOLICITAÇÃO: Troca de senha wifi, mudança de vencimento, mudança de plano, etc</li>
                        <li>PENDENTE TERCEIRO: falta de energia no loca, não tem por onde passar cabo da fibra, etc</li>
                    </ol>
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
<!-- modal cliente cadastrar-->';

include('rodape.php');
?>
<script>
    $('#chamados').addClass('active');
    //tabchamdos
    $().ready(function(){ tabela(); })
    function tabela(){
      $.ajax({
      type:'post',
      url:'tab-chamado.php',
      data:'html',
      success:function(data){
        $('#tabela').html(data);
      }
    });
    return false;
    };
    //exibir chamado
    function exibir(nchamado){
      window.open('exibir-chamado.php?id='+nchamado, '_blank');
    }
</script>