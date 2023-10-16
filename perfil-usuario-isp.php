<?php
include('topo.php');
$id = $_GET['id'];
$query = mysqli_query($conexao, "SELECT * FROM user WHERE id='$id'") or die(mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
        <div class="col-lg-12 colmd-12 col-sm-12 col-xs-12">
          <!-- Custom Tabs (Pulled to the right) -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li><a href="isp-financeiro.php?idcliente=' . $id . '">Financeiro</a></li>             
                <li class="active"><a href="#">Dados IPS</a></li>
                <li class="pull-left header"><i class="fa fa-th"></i> Cliente ISP</li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1-1">

                <form method="post" id="formAtualizarUsuario">
                <div class="row">
                <div class="col-lg-2">
                <label>Logo atual</br>';
if (!empty($dd['logomarca'])) {
  echo '<img src="logocli/' . $dd['logomarca'] . '"/>';
} else {
  echo '<i class="text-red">sem logomarca</i>';
}
echo '
                </label>
                
                </div>
                <div class="col-lg-10">
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">Código
                <input type="text" class="form-control" name="id" id="' . $dd['id'] . '" value="' . $dd['id'] . '" readonly/>
                </label>
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">Nome
                <input type="text" class="form-control" name="nome" value="' . $dd['nome'] . '"/>
                </label>
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">Fantasia
                <input type="text" class="form-control" name="fantasia" value="' . $dd['fantasia'] . '"/>
                </label>
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">E-mail
                <input type="text" class="form-control" name="email" value="' . $dd['email'] . '"/>
                </label>
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">CPF/CNPJ
                <input type="text" class="form-control" name="cpf_cnpj" value="' . $dd['cpf_cnpj'] . '"/>
                </label>              
                    
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">INSC.: Estaudal
                <input type="text" class="form-control" name="isestadual" value="' . $dd['isestadual'] . '"/>
                </label>
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">INSC.: MUNICIPAL
                <input type="text" class="form-control" name="ismunicipal" value="' . $dd['ismunicipal'] . '"/>
                </label>
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">Código IBGE
                <input type="text" class="form-control" name="codigoibge" value="' . $dd['codigoibge'] . '"/>
                </label>
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">Regime tributário
                <input type="text" class="form-control" name="regime" value="' . $dd['regime'] . '"/>
                </label>
    
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">Contato
                <input type="text" class="form-control celular" name="contato" value="' . $dd['contato'] . '"/>
                </label> 
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">Contato 2
                <input type="text" class="form-control celular" name="contato2" value="' . $dd['contato2'] . '"/>
                </label> 

                <div class="row"></div><hr>
                <label class="col-lg-2 col-md-4 col-sm-4 col-xs-12">CEP
                    <input type="text" class="form-control cep cepBusca" placeholder="Apenas números" name="cep" value="' . $dd['cep'] . '"/>
                </label>
                <label class="col-lg-4 col-md-8 col-sm-8 col-xs-12">Rua/Alameda/Avenida/etc
                    <input type="text" class="form-control enderecoBusca" placeholder="Rua/Alameda/Avenida/etc" name="rua" value="' . $dd['rua'] . '"/>
                </label>
                <label class="col-lg-2 col-md-4 col-sm-4 ">Bairro
                    <input type="text" class="form-control bairroBusca" placeholder="Bairro" name="bairro" value="' . $dd['bairro'] . '"/>
                </label>
                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-12">Múnicipio
                    <input type="text" class="form-control cidadeBusca" placeholder="Múnicipio" name="cidade" value="' . $dd['cidade'] . '"/>
                </label>
                <label class="col-lg-2 col-md-2 col-sm-4 col-xs-12">Estado
                    <input type="text" class="form-control ufBusca" placeholder="Estado" name="estado" value="' . $dd['estado'] . '"/>
                </label>
                <label class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><br />
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Atualizar</button>
                </label>
                </div>
                </div>                 
                </form>
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

include('rodape.php');
?>
<script>
  $('.gestao').addClass('active menu-open');
  $('#clientes2').addClass('active');
  //formAtualizarUsuario
  $('#formAtualizarUsuario').submit(function() {
    $.ajax({
      type: 'post',
      url: 'update-cliente-isp.php',
      data: $('#formAtualizarUsuario').serialize(),
      success: function(data) {
        $('#retorno').show().fadeOut(6000).html(data);
      }
    });
    return false;
  });
</script>