<?php 
include('topo.php');
/*
if(!empty($_GET['inicio']) AND !empty($_GET['fim'])){

	$inicio = $_GET['inicio'];
  $fim = $_GET['fim'];
  $query = mysqli_query($conexao,"SELECT cliente.*, contratos.nomeplano FROM cliente 
  LEFT JOIN contratos ON cliente.plano = plano.id
  WHERE cliente.idempresa='$idempresa' AND cliente.data BETWEEN '$inicio' AND '$fim' ORDER BY cliente.nome ASC") or die (mysqli_error($conexao));
  $n = 1;

}else if(!empty($_GET['situacao'])){

  $situacao = $_GET['situacao'];
  $query = mysqli_query($conexao,"SELECT * FROM cliente 
  WHERE idempresa='$idempresa' AND situacao='$situacao' ORDER BY nome ASC") or die (mysqli_error($conexao));
  $n = 1;

}else if(!empty($_GET['vencimento'])){
*/
if(!empty($_GET['inicio']) AND !empty($_GET['fim'])){
  $inicio = $_GET['inicio'];
  $fim = $_GET['fim'];
  $query = mysqli_query($conexao,"SELECT * FROM cobranca WHERE idempresa='$idempresa' AND vencimento BETWEEN '$inicio' AND '$fim' 
  AND valorpago='0.00' AND situacao <> 'CANCELADO' ORDER BY cobranca.cliente ASC") or die (mysqli_error($conexao));
  $rows = mysqli_num_rows($query);
}else{
  $rows = 0;
}
/*
}
/*else if(!empty($_GET['inicio']) AND !empty($_GET['fim']) AND !empty($_GET['situacao'])){
  $inicio = $_GET['inicio'];
  $fim = $_GET['fim'];
  $situacao = $_GET['situacao'];
  $variavel = "AND cliente.data BETWEEN '$inicio' AND '$fim' AND cliente.situacao='$situacao'";

}else if(!empty($_GET['vencimento'])  AND !empty($_GET['situacao'])){
  $vencimento = $_GET['vencimento'];
  $situacao = $_GET['situacao'];
    $variavel = "AND cliente.vencimento='$vencimento' AND cliente.situacao='$situacao'";
  
  }*/



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
            <label class="col-lg-4 col-md-4 col-sm-6 col-xs-12 hidden">
              <button onclick="teste()" class="btn btn-success btn-block"><i class="fa fa-file-pdf-o"></i> Gerar realatório</button>
            </label>
            </div>
        </form>
        <br/>
          <div class="box box-info">
            <div class="box-header with-border">
            <div class="col-lg-12">
              <center><h2>Relatório de vencimento</h2></center>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body>
            <div class="table-responsive">
                <table class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Dias</th>
                        <th>Situação</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>';
                if($rows >= 1 ){
                while($dd = mysqli_fetch_array($query)){
                  echo'
                    <tr>
                        <td><a href="clientes-exibir.php?id='.$dd['idcliente'].'" title="Acessar dados do cliente" target="_blank">'.$dd['cliente'].'</a></td>
                        <td>'.dataForm($dd['vencimento']).'</td>
                        <td>'.Real($dd['valor']).'</td>
                        <td>';
                        
                        // Calcula a diferença em segundos entre as datas
                        $diferenca = strtotime(date('Y-m-d')) - strtotime($dd['vencimento']);
                        //Calcula a diferença em dias
                        $dias = floor($diferenca / (60 * 60 * 24));                        
                        if($dias <= 0){ 
                          echo '<i style="color:green">'.$dias.' restam</i>';
                          }else{
                          echo '<i style="color:red">'.$dias.' atrasado</i>';  
                        } 
                        echo'</td>
                        <td>';if(@$dd['situacao'] == 'RECEBIDO'){ echo 'PAGO';}else{ echo'EM ABERTO';}echo'</td>
                        <td>
                            <a href="clientes-financeiro-exibir.php?id='.$dd['idcliente'].'" title="Acessar financeiro do cliente"><i class=" fa fa-dollar fa-2x text-green"></i></a>&ensp;

                        </td>
                    </tr>';

                    @$total = @$total + $dd['valor'];
                   
                 }
                 echo'
                 <tr style="background: gray; font-size: 18px; color: white">
                 <td></td>
                 <td><b>Total a receber</b></td>
                 <td><b>'.Real(@$total).'</b></td>
                 <td colspan="3"></td>
                </tr>               
                 
                 ';
                
                
                }echo'                
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
  $('#relatorios-relatorio').addClass('active');
  function teste(){
    let dia = $('#dia').val();
    let situacao = $('#situacao').val(); 
     $.get('gerar-pdf-clientes.php',{dia:dia,situacao:situacao},function(data){
       $('#retorno').show().html(data);
       });
       return false;
  };
</script>