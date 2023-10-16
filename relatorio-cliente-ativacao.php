<?php 
include('topo.php');

if(!empty($_GET['inicio']) AND !empty($_GET['fim'])){
  $inicio = $_GET['inicio'];
  $fim = $_GET['fim'];
  $query = mysqli_query($conexao,"SELECT contratos.*, cliente.nome FROM contratos 
  LEFT JOIN cliente ON contratos.idcliente = cliente.id
  WHERE contratos.idempresa='$idempresa' AND contratos.ativacao BETWEEN '$inicio' AND '$fim' 
  ORDER BY cliente.nome ASC") or die (mysqli_error($conexao));
  $rows = mysqli_num_rows($query);
}else{
  $rows = 0;
}


echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  
      <div class="row">
        <div class="col-xs-12">
        <form method="get">
          <div class="row">
            <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
          <input type="date" class="form-control" name="inicio" id="inicio" value="'.date(@$_GET['inicio']).'"/>
          </label>
            <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
          <input type="date" class="form-control" id="fim "name="fim" value="'.date(@$_GET['fim']).'"/>
          </label>
               
            <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <button class="btn btn-primary btn-block"><i class="fa fa-search"></i> Buscar</button>
            </label>
            <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <a href="relatorio-cliente.php" class="btn btn-default btn-block"><i class="fa fa-eraser"></i>  Limpar</a>
            </label>
            </div>
        </form>
        <br />
          <div class="box box-info">
            <div class="box-header with-border">
            <div class="col-lg-12">
              <center><h2>Relatório de ativação</h2></center>
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
                        <th>Cadadastro</th>
                        <th>Ativação</th>
                        <th>Plano</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>';
                if($rows >= 1 ){
                  $n = 1;
                while($dd = mysqli_fetch_array($query)){
                  echo'
                    <tr>
                      <td>'.$n.'</td>
                        <td><a href="clientes-exibir.php?id='.$dd['idcliente'].'" title="Acessar dados do cliente">'.$dd['nome'].'</a></td>
                        <td>'.dataForm($dd['data']).'</td>
                        <td>'.dataForm($dd['ativacao']).'</td>
                        <td>'.$dd['nomeplano'].'</td>
                        <td>
                            <a href="clientes-financeiro-exibir.php?id='.$dd['idcliente'].'" title="Acessar financeiro do cliente"><i class=" fa fa-dollar fa-2x text-green"></i></a>&ensp;
                        </td>
                    </tr>';
                    $n++;
                 }}echo'
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
  $('#relatorios-relatorio-ativacao').addClass('active');
</script>