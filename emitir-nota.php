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
                <h3 class="box-title">Emitir nota</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">ISS
                    <input type="text" class="form-control" placeholder="" name="iss" value="Exigível" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Estado
                    <input type="text" class="form-control" placeholder="" name="estado" value="PE" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Estado
                    <input type="text" class="form-control" placeholder="" name="municipio" value="Jaboatão dos Guararapes" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Execução do serviço
                    <input type="date" class="form-control" name="dataservico"/>
                </label>

                <div class="row"></div><hr>
                <h4 class="box-title">Identificação do cliente</h4>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">CNPJ
                    <input type="text" class="form-control cnpj" placeholder="CNPJ" name="cnpjcliente"/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">CPF
                    <input type="text" class="form-control cpf2" placeholder="CPF" name="cpfcliente"/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Nome/Razão social
                    <input type="text" class="form-control" placeholder="Nome/Razão social" name="nomerazao" />
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6"><br>
                    <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                </label>

                <div class="row"></div><hr>
                <h4 class="box-title">Dados do serviço prestado</h4>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Item de serviço da LC 116
                <div class="input-group input-group-sm">
                <input type="text" class="form-control" name="itemservico">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                </div>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Serviço municipal
                    <input type="text" class="form-control" placeholder="4321500" name="servicomunicipal" value="4321500" />
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Valor do serviço
                    <input type="text" class="form-control real" placeholder="0,00" name="valorserviço"/>
                </label>
                <label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrição
                    <textarea rows="3" class="form-control" name="descricao" required></textarea>
                </label>

                <div class="row"></div><hr>
                <h4 class="box-title">DEDUÇÕES, DESCONTOS E RETENÇÕES</h4>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">INSS retido
                    <input type="text" class="form-control" name="inssretido" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">IR retido
                    <input type="text" class="form-control" name="irretido" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">COFINS retido
                    <input type="text" class="form-control" name="cofinsretido" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">PIS/PASEP retido
                    <input type="text" class="form-control" name="pispasepretido" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">CSLL retido
                    <input type="text" class="form-control" name="csllpretido" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Deduções
                    <input type="text" class="form-control" name="deducoes" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Desconto condicionado
                    <input type="text" class="form-control" name="descontocondicionado" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Desconto incondicionado
                    <input type="text" class="form-control" name="descontoincondicionado" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Outras retenções
                    <input type="text" class="form-control" name="outrasretencoes" value="0,00" readonly/>
                </label>

                <div class="row"></div><hr>
                <h4 class="box-title">CARGA TRIBUTÁRIA APROXIMADA (Lei 12.741/2012)</h4>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Total dos tributos municipais
                    <input type="text" class="form-control" name="tributosmunicipais" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Total dos tributos estaduais
                    <input type="text" class="form-control" name="tributosestaduais" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Total dos tributos federais
                    <input type="text" class="form-control" name="tributosfederais" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Total dos tributos
                    <input type="text" class="form-control" name="totaltributos" value="0,00" readonly/>
                </label>

                <div class="row"></div><hr>
                <h4 class="box-title">APURAÇÕES</h4>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Base de cálculo
                    <input type="text" class="form-control" name="basedecalculo" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Alíquota
                    <input type="text" class="form-control" name="alicota" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Valor ISS
                    <input type="text" class="form-control" name="valoriss" value="0,00" readonly/>
                </label>
                <label class="col-xs-12 col-lg-3 col-md-3 col-sm-6">Valor líquido
                    <input type="text" class="form-control" name="valorliquido" value="0,00" readonly/>
                </label>

                <div class="row"></div><hr>
                <div class="col-xs-12 col-lg-4 col-md-4 col-sm-6">
                    <button class="btn btn-primary btn-block btn-lg margin">GERAR NFS-e</button>
                </div>
                <div class="col-xs-12 col-lg-4 col-md-4 col-sm-6">
                    <button class="btn btn-warning btn-block btn-lg margin">Prévia da NFS-e</button>
                </div>
                <div class="col-xs-12 col-lg-4 col-md-4 col-sm-6">
                    <button class="btn btn-danger btn-block btn-lg margin">Cancelar</button>
                </div>
          
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      </form>
      <!--/.row-->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';
include('rodape.php');
?>
<script>
    $('.notas').addClass('active menu-open');
    $('#emitir-notas').addClass('active');
</script>