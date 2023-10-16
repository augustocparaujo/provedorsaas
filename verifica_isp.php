<?php
@session_start();
include('conexao.php');
@$idempresa = $_SESSION['idempresa'];
//user_cobranca
//idcliente	link codigobarra	codigodelinhadigitavel	ncobranca	custom_id	cliente	vencimento	valor	datapagamento	situacao	pdf	qrcode	
$sql = mysqli_query($conexao,"select id from user where idempresa='$idempresa'");
$r = mysqli_fetch_array($sql);
$id = $r['id'];

$query = mysqli_query($conexao, "SELECT * FROM user_cobranca WHERE idcliente='$id' AND situacao IN ('PENDENTE','VENCIDO') ORDER BY vencimento DESC") or die(mysqli_error($conexao));
if (mysqli_num_rows($query) > 0) {

      
echo'
<div class="modal" style="display:block; padding-right: 17px;">
<div class="modal-dialog">
<div class="modal-content modal-lg">
<div class="modal-header">
<h4 class="modal-title">Aviso</h4><br />
<h4 class="modal-title text-red">Existe cobrança em aberto, regularize para voltar a ter acesso ao sistema</h4>
</div>
<div class="modal-body">
 <div class="box-body table-responsive no-padding">

                <table class="table table-hover">
                  <thead>
                  <tr>
                    <th>Id</th>
                    <th>Valor</th>
                    <th>Vencimento</th>
                    <th>Data/Pagamento</th>
                    <th>Dias/Vencidos</th>
                    <th>Situação</th>
                    <th>#</th>
                  </tr> 
                  </thead>
                  <tbody>';
    while ($dd = mysqli_fetch_array($query)) {
               //calculo entre datas
      $data_inicial = date('Y-m-d');
      $data_final = $dd['vencimento'];
      // Calcula a diferença em segundos entre as datas
      $diferenca = strtotime($data_final) - strtotime($data_inicial);
      //Calcula a diferença em dias
      $dias = floor($diferenca / (60 * 60 * 24)); 
      if($dias < 0){ $dias = abs($dias); }
      if($dias > 10 AND $dd['vencimento'] <= date('Y-m-d')){
        echo '
<tr>
    <td>' . $dd['ncobranca'] . '</td>
    <td>R$ ' . Real($dd['valor']) . '</td>
    <td>' . dataForm($dd['vencimento']) . '</td>
    <td>' . dataForm($dd['datapagamento']) . '</td>
    <td>'.diasVencidos($dd['vencimento']).'</td>
    <td>' . $dd['situacao'] . '</td>
    <td><a href="'.$dd['link'].'" target="_blank" title="Imprimir boleto"><i class="fa fa-barcode text-black fa-2x"></i></a></td>
</tr>';
    }}


echo'
</tbody>            
                </table>
              </div>
</div>
</div>
</div>
</div>';}