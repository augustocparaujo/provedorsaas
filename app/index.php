<?php
include('topo.php');
$query = mysqli_query($conexao, "SELECT * FROM cliente WHERE id='$iduser' AND idempresa='$idempresa'") or die(mysqli_error($conexao));
$dd = mysqli_fetch_array($query);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content" style="font-size:75% !important; ">
    <!-- linha 1-->
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <h3><i class="fa fa-user"></i> <?php echo 'Seja bem vindo(a):<br /><i class="text-primary"> ' . $_SESSION['usuario']; ?></i></h3>
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Dados</h3>
          </div>
          <div class="box-body no-padding">
            <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Situação
              <input type="text" class="form-control" name="situacao" value="<?php echo $dd['situacao']; ?>" />
              </input>
            </label>

            <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Vencimento
              <input type="number" class="form-control" placeholder="Vencimento" name="vencimento" value="<?php echo $dd['vencimento']; ?>" />
            </label>

            <label class="col-lg-4 col-md-6 col-sm-12 col-xs-12">Nome
              <input type="text" class="form-control" placeholder="Nome" name="nome" value="<?php echo $dd['nome']; ?>" required />
            </label>

            <?php if ($dd['tipo'] == 'Física') { ?>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">CPF
                <input type="text" class="form-control cpf2" placeholder="Apenas números" name="cpf" value="<?php echo $dd['cpf']; ?>" />
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">RG
                <input type="text" class="form-control" placeholder="Apenas números" name="rg" value="<?php echo $dd['rg']; ?>" />
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Estado RG
                <input type="text" class="form-control" name="rguf" value="<?php echo $dd['rguf']; ?>">
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Orgão emissor
                <input type="text" class="form-control" placeholder="Orgão emissor" name="emissor" value="<?php echo $dd['emissor']; ?>" />
              </label>
            <?php } ?>


            <?php if ($dd['tipo'] == 'Jurídica') { ?>
              <label class="col-lg-4 col-md-6 col-sm-12 col-xs-12">Fantasia
                <input type="text" class="form-control" placeholder="Fantasia" name="fantasia" value="<?php echo $dd['fantasia']; ?>" />
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">CNPJ
                <input type="text" class="form-control cnpj" placeholder="Apenas números" name="cnpj" value="<?php echo $dd['cnpj']; ?>" />
              </label>
              <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Incrição estadual
                <input type="text" class="form-control" placeholder="Apenas números" name="ie" value="<?php echo @$dd['ie']; ?>" />
              </label>
            <?php } ?>


            <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12"><b id="nascimento">Nascimento</b>
              <input type="text" class="form-control data" placeholder="00-00-0000" name="nascimento" value="<?php echo dataForm(@$dd['nascimento']); ?>" />
            </label>

            <div class="row"></div>
            <hr>
            <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Contato/whatsapp
              <input type="text" class="form-control celular" placeholder="Apenas números" name="contato" value="<?php echo $dd['contato']; ?>" />
            </label>
            <label class="col-lg-2 col-md-6 col-sm-12 col-xs-12">Contato 2
              <input type="text" class="form-control celular" placeholder="Apenas números" name="contato2" value="<?php echo @$dd['contato2']; ?>" />
            </label>
            <label class="col-lg-4 col-md-6 col-sm-6 col-xs-12">E-mail
              <input type="text" class="form-control" placeholder="E-mail" name="email" value="<?php echo @$dd['email']; ?>" />
            </label>

            <div class="row"></div>

            <div class="row"></div>
            <hr>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">CEP
              <input type="text" class="form-control cepBusca" placeholder="Apenas números" name="cep" value="<?php echo $dd['cep']; ?>" />
            </label>
            <label class="col-lg-4 col-md-6 col-sm-4 col-xs-12">Rua/Alameda/Avenida/etc
              <input type="text" class="form-control enderecoBusca" placeholder="Rua/Alameda/Avenida/etc" name="rua" value="<?php echo $dd['rua']; ?>" required />
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Número
              <input type="text" class="form-control" placeholder="Número" name="numero" value="<?php echo $dd['numero']; ?>" />
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Bairro
              <input type="text" class="form-control bairroBusca" placeholder="Bairro" name="bairro" value="<?php echo $dd['bairro']; ?>" required />
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Mnicipio
              <input type="text" class="form-control cidadeBusca" placeholder="Múnicipio" name="municipio" value="<?php echo $dd['municipio']; ?>" required />
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Estado
              <input type="text" class="form-control ufBusca" placeholder="Estado" name="estado" value="<?php echo $dd['estado']; ?>" required />
            </label>
            <label class="col-lg-2 col-md-6 col-sm-2 col-xs-12">Código IBGE
              <input type="text" class="form-control ibgeBusca" placeholder="IBGE" name="ibge" value="<?php echo $dd['ibge']; ?>" />
            </label>
            <label class="col-lg-4 col-md-6 col-sm-4 col-xs-12">Complemento
              <input type="text" class="form-control" placeholder="Complemento" id="cpf" name="complemento" value="<?php echo AspasForm($dd['complemento']); ?>" />
            </label>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include('rodape.php'); ?>