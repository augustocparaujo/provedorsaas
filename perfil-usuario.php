<?php
include('topo.php');
$id = $_SESSION['iduser'];
$query = mysqli_query($conexao, "SELECT * FROM user WHERE id='$id'") or die(mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  
     <form method="post" id="formAtualizarUsuario">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Atualizar cadastro</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-default" onclick="history.back();"><i class="fa  fa-chevron-left"></i> Voltar</button>
              	<button type="submit" class="btn btn-warning"><i class="fa fa-floppy-o"></i> Atualizar</button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <input type="text" class="hidden" name="id" value="' . $dd['id'] . '"/>
              <label class="col-lg-4 col-md-4 col-sm-6">Nome
                <input type="text" class="form-control" name="nome" value="' . $dd['nome'] . '"/>
              </label>
              <label class="col-lg-4 col-md-4 col-sm-6">Fantasia
                <input type="text" class="form-control" name="fantasia" value="' . $dd['fantasia'] . '"/>
              </label>
              <label class="col-lg-4 col-md-4 col-sm-6">E-mail
                <input type="text" class="form-control" name="email" value="' . $dd['email'] . '"/>
              </label>
              <label class="col-lg-4 col-md-4 col-sm-6">CPF/CNPJ
                <input type="text" class="form-control" name="cpf_cnpj" value="' . $dd['cpf_cnpj'] . '"/>
              </label>
              
              
              <label class="col-lg-4 col-md-4 col-sm-6">INSC.: Estaudal
                <input type="text" class="form-control" name="isestadual" value="' . $dd['isestadual'] . '"/>
              </label>
              <label class="col-lg-4 col-md-4 col-sm-6">INSC.: MUNICIPAL
                <input type="text" class="form-control" name="ismunicipal" value="' . $dd['ismunicipal'] . '"/>
              </label>
              <label class="col-lg-4 col-md-4 col-sm-6">Código IBGE
                <input type="text" class="form-control" name="codigoibge" value="' . $dd['codigoibge'] . '"/>
              </label>
              <label class="col-lg-4 col-md-4 col-sm-6">Regime tributário
                <input type="text" class="form-control" name="regime" value="' . $dd['regime'] . '"/>
              </label>
              
              
              <label class="col-lg-4 col-md-4 col-sm-6">Contato
                <input type="text" class="form-control celular" name="contato" value="' . $dd['contato'] . '"/>
              </label> 
              <label class="col-lg-4 col-md-4 col-sm-6">Contato 2
                <input type="text" class="form-control celular" name="contato2" value="' . $dd['contato2'] . '"/>
              </label> 

              <div class="row"></div><hr>
		        	<label class="col-lg-4 col-md-4 col-sm-4">CEP
			        	<input type="text" class="form-control cep cepBusca" placeholder="Apenas números" name="cep" value="' . $dd['cep'] . '"/>
		        	</label>
		        	<label class="col-lg-8 col-md-8 col-sm-8">Rua/Alameda/Avenida/etc
			        	<input type="text" class="form-control enderecoBusca" placeholder="Rua/Alameda/Avenida/etc" name="rua" value="' . $dd['rua'] . '"/>
		        	</label>
		        	<label class="col-lg-6 col-md-6 col-sm-6">Bairro
			        	<input type="text" class="form-control bairroBusca" placeholder="Bairro" name="bairro" value="' . $dd['bairro'] . '"/>
		        	</label>
		        	<label class="col-lg-6 col-md-6 col-sm-6">Múnicipio
			        	<input type="text" class="form-control cidadeBusca" placeholder="Múnicipio" name="cidade" value="' . $dd['cidade'] . '"/>
		        	</label>
		        	<label class="col-lg-6 col-md-6 col-sm-6">Estado
			        	<input type="text" class="form-control ufBusca" placeholder="Estado" name="estado" value="' . $dd['estado'] . '"/>
		        	</label>

              <div class="row"></div><hr>
              <label class="col-lg-4 col-md-4 col-sm-6">Alterar usuário
                <input type="text" class="form-control" placeholder="Login" name="login"/>
              </label>        
              <label class="col-lg-4 col-md-4 col-sm-6">Alterar senha
                <input type="password" class="form-control" placeholder="Senha" name="senha"/>
              </label>
              </form>
              <div class="row"></div><hr>
              <form method="post" id="formLogomarca" enctype="multipart/form-data">
              <input type="text" class="hidden" name="id" value="' . $dd['id'] . '"/>
              <input type="text" class="hidden" name="arquivologo" value="' . $dd['logomarca'] . '"/>
              <label class="col-lg-4 col-md-4 col-sm-4">Logomarca (150x150 - .jpg ou .png)
                <input type="file" class="form-control" name="arquivo" accept="image/jpg, image/png", required/>
              </label>
              <label class="col-lg-2 col-md-2 col-sm-2"><br />
              <button type="submit">enviar</button>
              </label>
              </form>   
              <label class="col-lg-6 col-md-6 col-sm-6">Logo atual</br>';
if (!empty($dd['logomarca'])) {
  echo '<img src="logocli/' . $dd['logomarca'] . '"/>';
} else {
  echo '<i class="text-red">sem logomarca</i>';
}
echo '</label>
                         
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

include('rodape.php');
?>
<script>
  //formAtualizarUsuario
  $('#formAtualizarUsuario').submit(function() {
    $.ajax({
      type: 'post',
      url: 'update-cliente-mk.php',
      data: $('#formAtualizarUsuario').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(6000).html(data);
      }
    });
    return false;
  });
  //enviar logo
  $('#formLogomarca').submit(function() {
    var formData = new FormData(this);
    $.ajax({
      type: 'POST',
      url: 'insert-logomarca-cliente-mk.php',
      data: formData,
      success: function(data) {
        $('#retorno').show().fadeOut(6000).html(data);
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
</script>