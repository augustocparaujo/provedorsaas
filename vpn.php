<?php
include('topo.php');
echo '
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">   
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">          
            <!-- /.box-header -->
            <div class="box-body">

                <div class="box-header ui-sortable-handle">
                    <i class="fa fa-th"></i>
                    <h3 class="box-title">Servidor VPN</h3>
                    <div class="box-tools pull-right">
                        <button class="btn bg-purple" data-toggle="modal" data-target="#"><i class="fa fa-plug"></i> Conexão servidor</button>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCad"><i class="fa fa-plus"></i> Criar VPN</button>
                    </div>
                </div>    

                <div class="box-body">            
                  <div class="table-responsive no-padding">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                              <th>#</th>
                                <th>Login</th>
                                <th>Senha</th>
                                <th>IP</th>
                                <th>Linha arquivo VPN</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                    </table>
                  </div>
                </div> 

            </div>
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
<!-- /.content-wrapper -->';

echo '
<!-- modal-->
<div class="modal" id="modalCad" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Criar VPN</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCad" autocomplete="off">
      <div class="modal-body">
    
           <div class="row">
            <label class="col-lg-12">Login
            <input type="text" class="form-control" placeholder="Login" name="login" required/>
            </label>

            <label class="col-lg-12">Senha
              <input type="type" class="form-control" name="senha" value="' . $senha . '" required/>
            </label>

            <label class="col-lg-12">IP
              <input type="type" class="form-control" name="ip" required/>
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
<!-- modal-->';

echo '
<!-- modal-->
<div class="modal" id="modalconf" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Configurar vpn</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="font-size:18px">
    
       
           <p>
           ### EXEMPLO DE COMO ESTARA O ARQUIVO ###<br />
           # Secrets for authentication using CHAP<br />
           # client        server  secret                  IP addresses<br />
           login * senha ip<br />
           adicionar aqui na última linha
           </p>
            <ol>
                <li>executar no terminal</li>
                <li> -> nano /etc/ppp/chap-secrets</ç>
                <li> -> "Control O"</li>
                <li> -> "Control X"</li>
            </ol>

          
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<!-- modal-->';


include('rodape.php');
?>
<script>
  $('.gestao').addClass('active menu-open');
  $('#vpn').addClass('active');
  $().ready(function() {
    tabela();
  });

  function tabela() {
    $.get('tab-vpn.php', function(data) {
      $('#tabela').show().html(data);
    });
    return false;
  }
  //adicina vpn
  $('#formCad').submit(function() {
    $('#modalCad').modal('hide');
    $.post({
      type: 'post',
      url: 'atualiza-vpn.php',
      data: $('#formCad').serialize(),
      success: function(data) {
        $('#retorno').show().html(data);
        tabela();
      }
    });
    return false;
  });
  //excluir
  function excluir(tipo, id) {
    var r = confirm("Deseja excluir? \n ATENÇÃO: Essa ação não tem volta! \nA exclusão na VPS precisa ser feita via terminal");
    if (r == true) {
      $.get('atualiza-vpn.php', {
        tipo: tipo,
        id: id
      }, function(data) {
        $('#retorno').show().html(data);
        tabela();
      });
      return false;
    }
  }
</script>