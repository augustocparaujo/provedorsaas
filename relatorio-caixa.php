<?php 
include('topo.php');
//verifica acesso
if($usercargo == 'Admin' || PermissaoCheck($idempresa,'relatorio-de-caixa',$iduser) == 'checked'){ 

@$datainicio = date('Y-m-01');
@$datafim = date('Y-m-t');
@$datahoje = date('Y-m-d');
//entradas mês
$sqle = mysqli_query($conexao, "SELECT SUM(valorpago) AS totalentradas FROM caixa WHERE idempresa='$idempresa' AND tipo='Entrada' AND datapagamento BETWEEN '$datainicio' AND '$datafim'") or die(mysqli_error($conexao));
$rete = mysqli_fetch_array($sqle);
//saídas mês
$sqls = mysqli_query($conexao, "SELECT SUM(valorpago) AS totalsaida FROM caixa WHERE idempresa='$idempresa' AND tipo='Saída' AND datapagamento BETWEEN '$datainicio' AND '$datafim'") or die(mysqli_error($conexao));
$rets = mysqli_fetch_array($sqls);
//entradas dia
$sqleh = mysqli_query($conexao, "SELECT SUM(valorpago) AS totalEhoje FROM caixa WHERE idempresa='$idempresa' AND tipo='Entrada' AND datapagamento BETWEEN '$datahoje' AND '$datahoje'") or die(mysqli_error($conexao));
$reteh = mysqli_fetch_array($sqleh);
//saída dia
$sqlsh = mysqli_query($conexao, "SELECT SUM(valorpago) AS totalShoje FROM caixa WHERE idempresa='$idempresa' AND tipo='Saída' AND datapagamento BETWEEN '$datahoje' AND '$datahoje'") or die(mysqli_error($conexao));
$retsh = mysqli_fetch_array($sqlsh);

echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">

    <div class="row">
        <form method="post" id="formRelatorio">
            <div class="col-lg-2 col-md-4 col-sm-12">Data ínicio
                <input type="date" name="inicio" class="form-control"/>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12">Data fim
                <input type="date" name="fim" class="form-control"/>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12">Tipo
                <select type="text" class="form-control" name="tipo">
                    <option value="">selecone</option>
                    <option value="Entrada">Entrada</option>
                    <option value="Saída">Saída</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12"><br>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Buscar</button>
            </div>
        </form>
    </div>
<br>

<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="col-lg-6 col-md-6 col-sm-6" style="background: white">
        <div style="background-color: green; color: white; text-align:center; font-weight:bold; font-size: 20px;">
            Entrada e saída do mês
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center; align-ifont-weight:bold; font-size: 20px">
            <p class="text-blue">Entradas: R$ '.Real(@$rete['totalentradas']).' </p>
            <p class="text-red">Saídas: R$ '.Real(@$rets['totalsaida']).' </p>
            <p>Total em caixa: R$ '.Real(@$rete['totalentradas'] - @$rets['totalsaida']).'</p>
        </div>

    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="background: white">
    <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: blue; color: white; text-align:center; font-weight:bold; font-size: 20px">
        Entrada e saída diário
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center; align-ifont-weight:bold; font-size: 20px">
        <p class="text-blue">Entradas: R$ '.Real(@$reteh['totalEhoje']).' </p>
        <p class="text-red">Saídas: R$ '.Real(@$retsh['totalShoje']).' </p>
        <p>Total em caixa: R$ '.Real(@$reteh['totalEhoje'] - @$retsh['totalShoje']).'</p>
    </div>
</div>
</div>
</div>
<br>

    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Relatório de caixa</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Descrição</th>
                        <th>Data</th>
                        <th>Valor</th>
                    </tr>
                </tbody>
                <tfoot id="tabela">
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';
}else{echo '<script>location.href="index.php";</script>';}

include('rodape.php'); ?>
<script>
    $('#relatoriocaixa').addClass('active');
    //tabela
    $().ready(function(){ tabela(); });
    function tabela(){
        $.ajax({
            type:'post',
            url:'tab-relatorio.php',
            data:'html',
            success: function(data){
                $('#tabela').show().html(data);
            }
        });
        return false;
    };
    $('#formRelatorio').submit(function(){
        $('#processando').modal('show');
        $.ajax({
            type:'post',
            url:'tab-relatorio.php',
            data:$('#formRelatorio').serialize(),
            success:function(data){
                $('#processando').modal('hide');
                $('#tabela').show().html(data);
            }
        });
        return false;
    });
</script>