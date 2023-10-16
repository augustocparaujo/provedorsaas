<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$empresa = @$_SESSION['idempresa'];
// Pegar o primeiro e último dia.
@$nome = $_POST['nome'];
//situacção
@$situacao = $_POST['situacao'];
//dia de pagamento
@$diapagamento = $_POST['diapagamento'];
// Pegar o último dia.
if(@$_POST['inicio'] == ''){ @$inicio = date('Y-m-01'); } else { @$inicio = $_POST['inicio']; }
if(@$_POST['fim'] == ''){ @$fim = date('Y-m-t'); } else { @$fim = $_POST['fim']; }
$n = 1;
if(!empty($inicio) AND !empty($fim)){

$sql = mysqli_query($conexao, "SELECT * FROM cobranca WHERE idempresa='$empresa' AND vencimento 
BETWEEN '$inicio' AND '$fim' AND cliente LIKE '%$nome%' AND situacao LIKE '%$situacao%' AND vencimento LIKE '%$diapagamento' ORDER BY cliente ASC") or die(mysqli_error($conexao));

if(mysqli_num_rows($sql) >= 1){
while ($dd = mysqli_fetch_array($sql)) {
        echo'<tr>
            <td>';
            if($dd['situacao'] == 'RECEBIDO'){ 
                echo'<span class="label label-success">'.$dd['situacao'].'</span>';
            }else{
                echo'<span class="label label-danger">'.$dd['situacao'].'</span>';
            }
            echo'</td>
            <td style="cursor: pointer; color: blue" onclick="exibir('.$dd['idcliente'].')">'.$dd['cliente'].'</td>
            <td>'.Real($dd['valorpago']).'</td>
            <td>'.date('d-m-Y',strtotime($dd['vencimento'])).'</td>
            <td>'; if($dd['datapagamento'] != '0000-00-00'){ echo dataForm($dd['datapagamento']); } echo'</td>
            <td></td>
            <td>   
                <a onclick="alert0()" class="btn btn-social-icon btn-dollar"><i class="fa fa-dollar"></i></a> &ensp;
                <a onclick="alert0()" class="btn btn-social-icon btn-trash"><i class="fa fa-trash"></i></a>  &ensp; 
                <a onclick="alert0()" class="btn btn-social-icon btn-edit" onclick="exibir('.$dd['idcliente'].')"><i class="fa fa-edit"></i></a>
            </td>
        </tr>';
       if($dd['situacao'] == 'RECEBIDO'){ @$tentrada = @$tentrada + $dd['valorpago']; }
       if($dd['situacao'] == 'PENDENTE' OR $dd['situacao'] == 'VENCIDO'){ @$tsaida = @$tsaida + $dd['valor']; }
       $n++;
}
echo'
<tr style="background: #9AFF9A; font-weight: bold">
    <td colspan="4"></td>
    <td>Recebido</td>
    <td>'.Real(@$tentrada).'</td>
    <td colspan="3"></td>
</tr>
<tr style="background: #FF8C69; font-weight: bold">
    <td colspan="4"></td>
    <td>A receber</td>
    <td>'.Real(@$tsaida).'</td>
    <td colspan="3"></td>
</tr>
';
}else{
    echo'<div class="col-lg-12 text-red">Sem movimento</div>';
}
}else{
    echo'<div class="col-lg-12 text-red">Sem dados</div>';
}
?>