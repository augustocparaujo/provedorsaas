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
echo'Ao final ira aparecer todos os clientes verificados para bloqueio <br /> <i style="color:red">Aguarde realizando processo...</i><hr>';
set_time_limit(0);
ob_start();
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_GET['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$iduser = $_SESSION['iduser'];
if(isset($_SESSION['iduser'])!=true){echo '<script>location.href="sair.php";</script>'; }

require_once('routeros_api.class.php');
$mk = new RouterosAPI();
$agora = date('d-m-Y H:m:s'); 
$hoje = date('Y-m-d');
$inicio = date('Y-m-01');
$fim = date('Y-m-t');

$sql = mysqli_query($conexao,"SELECT cobranca.*, contratos.plano,contratos.id as contratoid,contratos.login,contratos.situacao, plano.servidor, servidor.ip,servidor.user,servidor.password FROM cobranca
LEFT JOIN contratos ON cobranca.idcliente = contratos.idcliente
LEFT JOIN plano ON contratos.plano = plano.id
LEFT JOIN servidor ON plano.servidor = servidor.id
WHERE cobranca.situacao='VENCIDO' AND contratos.situacao='Ativo' AND cobranca.situacao <> 'CANCELADO' AND cobranca.banco <> 'ISENTO' AND cobranca.idempresa='$idempresa' ORDER BY cobranca.vencimento ASC") or die (mysqli_error($conexao));
$row = mysqli_num_rows($sql);
$i = 1;
echo'<table>
<tr>
    <th>N°</th>
    <th>Cliente</th>
    <th>Tipo</th>
    <th>Cobranca</th>
    <th>Valor</th>
    <th>Vencido</th>
    <th>Dia bloqueio</th>
    <th>Ação</th>
</tr>';
while($ret = mysqli_fetch_array($sql)){
    if(@$contrato != $ret['contratoid']){
        //dias para bloqueio
        $sql1 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE idempresa='$idempresa' AND diasbloqueio > 0 AND bloqueioautomatico='sim'") or die (mysqli_error($conexao));
        $rows = mysqli_num_rows($sql1);
        $retd = mysqli_fetch_array($sql1);
        //calcular dia para bloqueio
        $diasbloqueio = $retd['diasbloqueio'];
        $diabloqueio = date('Y-m-d', strtotime('+'.$diasbloqueio.' days', strtotime($ret['vencimento'])));
            /*
                        // Calcula a diferença em segundos entre as datas
                $diferenca = strtotime($diabloqueio) - strtotime(date('Y-m-d'));
                 //Calcula a diferença em dias
                $dias = floor($diferenca / (60 * 60 * 24));
                echo $dias;

                */
        if(date('Y-m-d') >= $diabloqueio){

            @$idempresa = @$ret['idempresa'];
            @$idcliente = @$ret['idcliente'];
            @$plano = @$ret['plano'];
            @$login = @$ret['login'];
            @$servidor = @$ret['servidor'];
            @$ip = @$ret['ip'];
            @$user = @$ret['user'];
            @$password = @$ret['password'];

     
            //trocar profile para reduzido
            if($mk->connect($ip, decrypt($user), decrypt($password))) {
                $find = @$mk->comm("/ppp/secret/print", array("?name" =>  utf8_decode($login))); 
                if (count($find) >= 1) {
                    //print_r($find);
                    $Finduser  = $find[0];
                    //atualiza o secret
                    $find = $mk->comm("/ppp/secret/set", array(
                        ".id" =>  $Finduser['.id'],
                        "name" => utf8_decode($login),
                        "profile" => "Bloqueados"
                    ));
                    //derruba a conexão ativa para atualizar
                    $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));
                    if (count($find) >= 1) {
                        $Finduser  = $find[0];
                        $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                    }
                    $acao = 'Bloqueado';
                }
            }
            //alterar situação tabela contratos e cliente
            mysqli_query($conexao,"UPDATE cliente SET situacao='Bloqueado' WHERE id='$idcliente'") or die (mysqli_error($conexao));
            mysqli_query($conexao,"UPDATE contratos SET situacao='Bloqueado' WHERE idcliente='$idcliente'") or die (mysqli_error($conexao));
            //exibir os alterados
  
            echo '           
            <tr>
                <td>'.@$i.'</td>
                <td><a href="clientes-financeiro-exibir.php?id='.$idcliente.'" target="_blank">'.$ret['cliente'].'</a></td>
                <td>'.$ret['tipo'].'</td>
                <td>'.$ret['code'].'</td>
                <td>'.Real($ret['valor']).'</td>
                <td>'.dataForm($ret['vencimento']).'</td>
                <td>'.dataForm($diabloqueio).'</td>
                <td><i style="color:red">Será bloqueado</i></td>
            </tr>';
        }
        $i++;
        
       
    }
    $contrato = $ret['contratoid'];
        //echo 'N°: '.$i.' / Cliente: '.$ret['cliente'].' / Tipo: '.$ret['tipo'].' / Cobranca: '.$ret['code'].' / Valor: '.Real($ret['valor']).' / Vencido: '.dataForm($ret['vencimento']).' / Dia redução: '.$diabloqueio.' / Ação: Não bloqueado<hr>';
}
echo'</table>';
echo'<i style="color:red">Finalizado processo...</i><hr>';

?>