<?php 
include('topo.php');

if(@$_POST['inicio'] != ''){ @$inicio = $_POST['inicio']; }else{ @$inicio = date('Y-m-01');}
if(@$_POST['fim'] != ''){ @$fim = $_POST['fim']; }else{ @$fim = date('Y-m-t');}
if(@$_POST['tipo'] != ''){ $tipo = $_POST['tipo']; }else{ $tipo = '';}
$hoje = date('Y-m-d');

$sql = mysqli_query($conexao, "SELECT * FROM caixa 
WHERE caixa.idempresa='$idempresa' AND 
datapagamento BETWEEN '$inicio' AND '$fim' AND tipo LIKE '%$tipo%' ORDER BY caixa.datapagamento ASC"
) or die(mysqli_error($conexao));

//entradas mês
$sqle = mysqli_query($conexao, "SELECT SUM(valorpago) AS totalentradas FROM caixa WHERE 
idempresa='$idempresa' AND tipo='Entrada' AND datapagamento BETWEEN '$inicio' AND '$fim'") or die(mysqli_error($conexao));
$rete = mysqli_fetch_array($sqle);
//saídas mês
$sqls = mysqli_query($conexao, "SELECT SUM(valorpago) AS totalsaida FROM caixa WHERE 
idempresa='$idempresa' AND tipo='Saída' AND datapagamento BETWEEN '$inicio' AND '$fim'") or die(mysqli_error($conexao));
$rets = mysqli_fetch_array($sqls);
//entradas dia
$sqleh = mysqli_query($conexao, "SELECT SUM(valorpago) AS totalEhoje FROM caixa WHERE 
idempresa='$idempresa' AND tipo='Entrada' AND datapagamento BETWEEN '$hoje' AND '$hoje'") or die(mysqli_error($conexao));
$reteh = mysqli_fetch_array($sqleh);
//saída dia
$sqlsh = mysqli_query($conexao, "SELECT SUM(valorpago) AS totalShoje FROM caixa WHERE 
idempresa='$idempresa' AND tipo='Saída' AND datapagamento BETWEEN '$hoje' AND '$hoje'") or die(mysqli_error($conexao));
$retsh = mysqli_fetch_array($sqlsh);

echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">

    <div class="row">
        <form method="post" action="controle-de-caixa.php">
            <div class="col-lg-2 col-md-4 col-sm-12">Data ínicio
                <input type="date" name="inicio" class="form-control" value="'.date(@$inicio).'"/>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12">Data fim
                <input type="date" name="fim" class="form-control" value="'.date(@$fim).'"/>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-12">Tipo
                <select type="text" class="form-control" name="tipo">
                    <option value="'.@$tipo.'">selecone</option>
                    <option value="Entrada">Entrada</option>
                    <option value="Saída">Saída</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12"><br>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Buscar</button>
            </div>
        </form>';
        if($_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'controle-caixa-cadastrar',$iduser) == 'checked'){ echo'
            <br/><button class="btn btn-primary" data-toggle="modal" data-target="#movimentoCadastrar"><i class="fa fa-plus"></i> Cadastrar</button>';
        }echo'
    </div>
<br>

<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="col-lg-6 col-md-6 col-sm-6" style="background: white">
        <div style="background-color: green; color: white; text-align:center; font-weight:bold; font-size: 20px;">
            Entrada e saída do mês ou datas selecionadas
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center; align-ifont-weight:bold; font-size: 20px">
            <p class="text-blue">Entradas: R$ '.Real(@$rete['totalentradas']).' </p>
            <p class="text-red">Saídas: R$ '.Real(@$rets['totalsaida']).' </p>';
            $caixa = @$rete['totalentradas'] - @$rets['totalsaida'];
            if($caixa < -1){ echo '<p style="color:red"><b>Total em caixa: R$ '.Real($caixa).'</b></p>';}else{ echo'<p style="color:green"><b>Total em caixa: R$ '.Real($caixa).'</b></p>';}
            echo'
        </div>

    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="background: white">
    <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: blue; color: white; text-align:center; font-weight:bold; font-size: 20px">
        Entrada e saída diário
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center; align-ifont-weight:bold; font-size: 20px">
        <p class="text-blue">Entradas: R$ '.Real(@$reteh['totalEhoje']).' </p>
        <p class="text-red">Saídas: R$ '.Real(@$retsh['totalShoje']).' </p>';
        $caixadia = @$reteh['totalEhoje'] - @$retsh['totalShoje'];
        if($caixadia < -1){ echo '<p style="color:red"><b>Total em caixa: R$ '.Real($caixadia).'</b></p>';}else{ echo'<p style="color:green"><b>Total em caixa: R$ '.Real($caixadia).'</b></p>';}echo'
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
                        <th>Banco</th>
                        <th>Cliente</th>
                        <th>Descrição</th>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>#</th>
                    </tr>
                </tbody>
                <tfoot>';   
                $n = 1;             
                    if(mysqli_num_rows($sql) >= 1){
                    while ($dd = mysqli_fetch_array($sql)) {
                            echo'<tr>
                                <td>'.@$n.'</td>
                                <td>'; if($dd['tipo'] == 'Entrada'){ echo '<i class="text-green">Entrada</i>';}else{ echo'<i class="text-red">Sáida</i>';} echo'</td>
                                <td>'.$dd['banco'].'</td>
                                <td>'.@$dd['nomecliente'].'</td>
                                <td>'.$dd['descricao'].'</td>
                                <td>'.date('d-m-Y',strtotime($dd['datapagamento'])).'</td>
                                <td>'.Real($dd['valorpago']).'</td>
                                <td>';
                                if($_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'controle-caixa-excluir',$iduser) == 'checked'){ echo'
                                <a href="#" onclick="excluir('.$dd['id'].')" title="excluir"><i class="fa fa-trash fa-2x text-red"></i></a>&ensp;';
                                }
                                if($_SESSION['tipouser'] == 'Admin' || PermissaoCheck($idempresa,'controle-caixa-exibir',$iduser) == 'checked'){ echo'
                                    <a href="#" onclick="exibirRecebido('.$dd['id'].')" title="eixibir"><i class="fa fa-file-text-o fa-2x text-blue"></i></a>';
                                }echo'
                                </td>
                            </tr>';
                            $n++;
                    }
                    }else{
                        echo'<div class="col-lg-12 text-red">Sem movimento</div>';
                    }echo'
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
<!-- /.content-wrapper -->

<!-- modal cliente cadastrar-->
<div class="modal" id="movimentoCadastrar" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAddCaixa" autocomplete="off">
      <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
         <label class="col-lg-12">Tipo
                <select type="text" class="form-control" name="tipo">
                <option value="Entrada">Entrada</option>                    
                  <option value="Saída">Saída</option>
                </select>
            </label>
            <label class="col-lg-12">Cartão Crédito
              <input type="text" class="form-control real"  name="cartaocredito"/>
            </label>
            <label class="col-lg-12">Cartão débito
                <input type="text" class="form-control real"  name="cartaodebito"/>
            </label>
            <label class="col-lg-12">PIX
                <input type="text" class="form-control real"  name="pix"/>
            </label>
            <label class="col-lg-12">Dinheiro
                <input type="text" class="form-control real"  name="dinheiro"/>
            </label>
            <label class="col-lg-12">Data
                <input type="date" class="form-control" name="data"/>
            </label> 
            <label class="col-lg-12">Descrição
              <textarea rows="2" class="form-control" name="descricao" required></textarea>
            </label>   
            </div>
            </div>       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- modal addcaixa-->  

<!-- modal exibir recebimento-->
<div class="modal" id="modalTeste" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cobrança</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="row" id="retornoRR">
                
            </div>       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<!-- modal exibir recebimento--> 

';

include('rodape.php'); ?>
<script>
    $('.financeiro').addClass('active menu-open');
    $('#controle-caixa').addClass('active');
        //formAddCaixa
    $('#formAddCaixa').submit(function(){
    $('#movimentoCadastrar').modal('hide');
      $('#processando').modal('show');
    	$.post({
    		type:'post',
    		url:'insert-caixa.php',
    		data:$('#formAddCaixa').serialize(),
    		success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(3000).html(data);
          window.setTimeout(function() { history.go(); }, 3001);
    		}
    	});
    	return false;
    });
    //exibir recebido
    function exibirRecebido(id){
      $('#modalTeste').modal('show');
      $.get('exibir-caixa.php',{id:id},function(data){
          $('#retornoRR').show().html(data);
      });
      return false;
    };
</script>