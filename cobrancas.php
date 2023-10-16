<?php 
include('topo.php');
//verifica acesso
// Pegar o primeiro e último dia.
@$nome = $_POST['nome'];
//situacção
@$situacao = $_POST['situacao'];
//dia de pagamento
@$diapagamento = $_POST['diapagamento'];
// Pegar o último dia.
if(@$_POST['inicio'] == ''){ @$inicio = date('Y-m-01'); } else { @$inicio = $_POST['inicio']; }
if(@$_POST['fim'] == ''){ @$fim = date('Y-m-t'); } else { @$fim = $_POST['fim']; }
//detahes

//a receber
$sql0 = mysqli_query($conexao, "SELECT SUM(valor) AS areceber FROM cobranca WHERE idempresa='$idempresa' AND vencimento 
BETWEEN '$inicio' AND '$fim' AND cliente LIKE '%$nome%' AND situacao IN ('PENDENTE','VENCIDO')") or die(mysqli_error($conexao));
@$receber = mysqli_fetch_array($sql0);

//recebido
$sql1 = mysqli_query($conexao, "SELECT SUM(valorpago) AS recebido FROM cobranca WHERE idempresa='$idempresa' AND vencimento 
BETWEEN '$inicio' AND '$fim' AND cliente LIKE '%$nome%' AND situacao='RECEBIDO'") or die(mysqli_error($conexao));
@$recebido = mysqli_fetch_array($sql1);

//clientes bloqueados
$sql2 = mysqli_query($conexao,"SELECT * FROM cliente WHERE situacao='BLOQUEADO' AND idempresa='$idempresa'");
@$bloqueados = mysqli_num_rows($sql2);

//atrasados
$sql3 = mysqli_query($conexao, "SELECT * FROM cobranca WHERE idempresa='$idempresa' AND vencimento 
BETWEEN '$inicio' AND '$fim' AND situacao IN ('PENDENTE','VENCIDO') GROUP BY idcliente") or die(mysqli_error($conexao));
@$totatrasados = mysqli_num_rows($sql3);

//em dia
$sql4 = mysqli_query($conexao, "SELECT * FROM cobranca WHERE idempresa='$idempresa' AND vencimento 
BETWEEN '$inicio' AND '$fim' AND situacao='RECEBIDO' GROUP BY idcliente") or die(mysqli_error($conexao));
@$totemdia = mysqli_num_rows($sql4);

echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">

    <div class="row">
        <form method="post" action="cobrancas.php">
            <div class="col-lg-2 col-md-4 col-sm-12">Nome
                <input type="text" name="nome" class="form-control" value="'.@$nome.'"/>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12">Data ínicio
                <input type="date" name="inicio" class="form-control" value="'.@$inicio.'"/>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12">Data fim
                <input type="date" name="fim" class="form-control" value="'.@$fim.'"/>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12">Situação
                <select type="text" name="situacao" class="form-control">';
                    if(!empty(@$situacao)){ echo '<option value="'.@$situacao.'">'.@$situacao.'</option>';} echo'
                    <option value="">selecione</option>
                    <option value="PENDENTE">PENDENTE</option>
                    <option value="VENCIDO">VENCIDO</option>
                    <option value="RECEBIDO">RECEBIDO</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12"><br>
            <button type="submit" class="btn btn-default reclickar"><i class="fa fa-search"></i> Buscar</button>
            </div>
        </form>
    </div>
    <br>
    <div class="col-lg-12 col-md-12 col-sm-12" style="text-align: center">
    <p style="font-size: 16px; font-weight:bold"><span class="text-red">VALOR A RECEBER: R$ '.Real(@$receber['areceber']).'</span> &emsp; <span class="text-green">VALOR RECEBIDO: R$ '.Real(@$recebido['recebido']).'</span> 
    &emsp; CLIENTES: <span class="text-green">EM DIA: '.@$totemdia.'</span> &emsp; EM ATRASO: '.@$totatrasados.' <span class="text-red">&emsp; BLOQUEADOS: '.@$bloqueados.'</span> </p>
    </div>
<br>
    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Relatório de cobrança</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>Situação</th>
                        <th>Nome do cliente</th>
                        <th>Banco</th>
                        <th>Valor Pag.</th>
                        <th>Vencimento</th>
                        <th>Data Pag.</th>
                        <th>#</th>
                    </tr>
                </tbody>
                <tfoot>';
                $n = 1;
                $sql = mysqli_query($conexao, "SELECT * FROM cobranca WHERE idempresa='$idempresa' AND vencimento 
                BETWEEN '$inicio' AND '$fim' AND cliente LIKE '%$nome%' AND situacao LIKE '%$situacao%' AND vencimento LIKE '%$diapagamento' AND cobranca.situacao <> 'CANCELADO' ORDER BY cobranca.cliente,cobranca.situacao ASC") or die(mysqli_error($conexao));
                if(mysqli_num_rows($sql) >= 1){
                    while ($dd = mysqli_fetch_array($sql)) {
                        echo'<tr>
                            <td>'.@$n.'</td>
                            <td>';
                            if($dd['situacao'] == 'RECEBIDO'){ 
                                echo'<span class="label label-success">'.$dd['situacao'].'</span>';
                            }elseif($dd['situacao'] == 'PENDENTE'){
                                echo'<span class="label label-info">'.$dd['situacao'].'</span>';
                            }else{
                                echo'<span class="label label-danger">'.$dd['situacao'].'</span>';
                            }
                            echo'</td>
                            <td><a href="clientes-exibir.php?id='.$dd['idcliente'].'" target="_blank">'.$dd['cliente'].'</a></td>
                            <td>'.$dd['banco'].'</td>
                            <td>'.Real($dd['valorpago']).'</td>
                            <td>'.date('d-m-Y',strtotime($dd['vencimento'])).'</td>
                            <td>'; if($dd['datapagamento'] != '0000-00-00'){ echo dataForm($dd['datapagamento']); } echo'</td>
                            <td>';   
                                if($dd['situacao'] != 'RECEBIDO'){
                                    if(PermissaoCheck($idempresa,'cobranca-receber',$_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin'){echo'
                                        <a href="clientes-financeiro-exibir.php?id='.$dd['idcliente'].'" target="_blank"><i class="fa fa-dollar text-green fa-2x"></i></a>';
                                    }
                                }echo'
                            </td>
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
    $('.financeiro').addClass('active menu-open');
    $('#cobrancas').addClass('active');
    //excluirCobrancaSemBoleto
    function excluirCobrancaSemBoleto(id){
      var r = confirm("Deseja excluir?");
          if (r == true) {
            $.get('cancelar-sem-boleto.php',{id:id},function(data){
              $('#retorno').show().fadeOut(6000).html(data);
              window.setTimeout(function() { $('.reclickar').trigger('click'); }, 2001);
            });
          }
          return false;
    }
</script>