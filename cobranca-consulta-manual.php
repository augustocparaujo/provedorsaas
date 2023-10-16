<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<?php
//set_time_limit(0);
include('conexao.php');
$iniciomes = date('2022-12-01');
$fimmes = date('Y-m-t');
//criar função de verificação se estar vencida ou não e alterar a situacção
$query = mysqli_query($conexao,"SELECT cobranca.code,ncobranca,cliente,vencimento,valor,situacao FROM cobranca 
WHERE idempresa='9999999999' AND vencimento BETWEEN '$iniciomes' AND '$fimmes' AND situacao IN ('PENDENTE','VENCIDO') AND code <> ''
ORDER BY vencimento ASC") or die (mysqli_error($conexao));
$rows = mysqli_num_rows($query);
$n = 1;
if($rows >= 1){   
    echo'<h2 style="color:red">--->>> Listagem a ser verificada Data inicio: '.date('d-m-Y',strtotime($iniciomes)).' | Data fim: '.date('d-m-Y',strtotime($fimmes)).'</h2>
<table>
<tr>
    <th>N°</th>
    <th>Cliente</th>
    <th>Cobrança</th>
    <th>Valor</th>
    <th>Vencimento</th>
    <th>Status</th>
    <th>Callback</th>
</tr>';
    while($ret = mysqli_fetch_array($query)){
        $id = $ret['id'];         

        if(date('Y-m-d') > $ret['vencimento']){ $situacao = '<i style="color:red">VENCIDO</i>'; }else{ $situacao = '<i style="color:blue">PENDENTE</i>'; }
        echo' 
        <tr>
            <td>'.$n.'</td>
            <td>'.$ret['cliente'].'</td>
            <td>'.$ret['code'].'</td>
            <td>'.$ret['valor'].'</td>
            <td>'.date('d-m-Y',strtotime($ret['vencimento'])).'</td> 
            <td>'.$situacao.'</td>  
            <td>';
            
            require_once('api_bb.php');
            consultarCobranca2($id);

            echo'</td>     
        </tr>';
        $n++;
        sleep(10); //aguardar 10 segundos ate o proximpo loop
    }
    echo'</table>';
}
echo'<h2 style="color:red">--->>> Total de cobranças verificadas: '.$rows.'</h2>';

?>
