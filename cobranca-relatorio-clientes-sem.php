<?php 
include('topo.php');
if(!empty($_GET['inicio']) AND !empty($_GET['fim'])){
	$inicio = $_GET['inicio'];
  $fim = $_GET['fim'];
  $variavel = "AND cobranca.vencimento BETWEEN '$inicio' AND '$fim'";
}else{
  	$inicio = date('Y-m-01');
  $fim = date('Y-m-t');
$variavel = "AND cobranca.vencimento BETWEEN '$inicio' AND '$fim'";;
}

$query = mysqli_query($conexao,"select * from cliente where idempresa='$idempresa' 
AND id not in (select idcliente from cobranca WHERE idempresa='$idempresa' $variavel)") or die (mysqli_error($conexao));

/*
$query = mysqli_query($conexao,"select cliente.*, cobranca.vencimento AS venCob,cobranca.valor,cobranca.tipo,cobranca.situacao AS sitcob from cliente 
JOIN  cobranca ON cliente.id = cobranca.idcliente
where cliente.id not in (select idcliente from cobranca) AND cliente.idempresa='$idempresa' $variavel ORDER BY cliente.nome ASC") or die (mysqli_error($conexao));
/*
$query = mysqli_query($conexao,"SELECT cliente.*, cobranca.vencimento AS venCob,cobranca.valor,cobranca.tipo,cobranca.situacao AS sitcob FROM cliente
JOIN  cobranca ON cliente.id = cobranca.idcliente
WHERE cliente.idempresa='$idempresa' $variavel ORDER BY cliente.nome ASC") or die (mysqli_error($conexao));*/
$n = 1;echo'

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  
      <div class="row">
        <div class="col-xs-12">
        <form method="get">
          <div class="row">
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <input type="date" class="form-control" name="inicio" id="inicio" value="'.date(@$inicio).'"/>
            </label>
                <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <input type="date" class="form-control" id="fim "name="fim" value="'.date(@$fim).'"/>
            </label>
            <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <button class="btn btn-primary btn-block"><i class="fa fa-search"></i> Buscar</button>
            </label>
            </div>
        </form>
        <br/>
          <div class="box box-info">
            <div class="box-header with-border">
            <div class="col-lg-12">
              <center><h2>Relatório de clientes sem cobrança</h2></center>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body>    
            <div class="table-responsive">
                <table class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Vencimento</th>
                    </tr>
                </thead>
                <tbody>';

                while($dd = mysqli_fetch_array($query)){echo'
                    <tr>
                        <td>'.$n.'</td>
                        <td><a href="clientes-financeiro-exibir.php?id='.$dd['id'].'" target="_blank">'.strtoupper($dd['nome']).'</a></td>
                        <td>'.$dd['vencimento'].'</td>  
                    </tr>';
                $n++; }echo'

                </tbody>
                </table>
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
  $('.relatorios').addClass('active menu-open');
$('#relatorios-sem-cobranca').addClass('active');
</script>