<?php 
include('topo.php');
@$inicio = date('Y-m-01');
@$fim = date('Y-m-t');

echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Cobrança vencidas</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>Situação</th>
                        <th>Nome do cliente</th>
                        <th>Valor Pag.</th>
                        <th>Vencimento</th>
                        <th>Data Pag.</th>
                        <th>Status</th>
                        <th>#</th>
                    </tr>
                </tbody>
                <tfoot>';
                $n = 1;
                $sql = mysqli_query($conexao, "SELECT * FROM cobranca WHERE idempresa='$idempresa' AND vencimento BETWEEN '$inicio' AND '$fim' AND situacao='VENCIDO' ORDER BY cliente ASC") or die(mysqli_error($conexao));
                if(mysqli_num_rows($sql) >= 1){
                    while ($dd = mysqli_fetch_array($sql)) {
                        echo'<tr>
                            <td>'.@$n.'</td>
                            <td><span class="label label-danger">'.$dd['situacao'].'</span></td>
                            <td style="cursor: pointer; color: blue" onclick="exibir('.$dd['idcliente'].')">'.$dd['cliente'].'</td>
                            <td>'.Real($dd['valorpago']).'</td>
                            <td>'.date('d-m-Y',strtotime($dd['vencimento'])).'</td>
                            <td>'; if($dd['datapagamento'] != '0000-00-00'){ echo dataForm($dd['datapagamento']); } echo'</td>
                            <td></td>
                            <td><a onclick="exibir('.$dd['idcliente'].')" class="btn btn-social-icon btn-dollar"><i class="fa fa-dollar"></i></a></td>
                        </tr>';
                        $n++;
                    }
                }else{
                    echo'<div class="col-lg-12 text-red">Sem movimento</div>';
                }
            echo'
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

include('rodape.php'); ?>
<script>
    $('#cobrancas-vencidas').addClass('active');
    //exibir cliente
    function exibir(id){
      window.location.href='exibir-financeiro-cliente.php?id='+id;
    }
</script>