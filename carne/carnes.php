<?php
session_start();
include('../conexao.php'); 
include('../funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }

$querye = mysqli_query($conexao,"SELECT * FROM user WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
$rete = mysqli_fetch_array($querye);
//dados cobranca empresa
$queryc = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
$retc = mysqli_fetch_array($queryc);
$diasdesconto0 = $retc['diasdesconto'];
$valordesconto = Real($retc['valordesconto']);
if (!$rete['nome']) { $nome_empresa = strtoupper($rete['nome']); } else { $nome_empresa = strtoupper($rete['fantasia']); }
if (!$rete['rua']) { $endereco_empresa = ""; } else { $endereco_empresa = addslashes($rete['rua'].'-'.$rete['bairro'].'-'.$rete['cidade'].'/'.$rete['estado']); }
if (!$rete['contato']) { $tel_empresa = ""; } else { $tel_empresa = addslashes($rete['contato']); }
if (!$logomarcauser) { $logo = ""; } else { $logo = $logomarcauser; }
if (!$rete['cpf_cnpj']) { $docempresa = ""; } else { $docempresa = $rete['cpf_cnpj']; }
$hoje = date("d/m/Y");
$id = $_GET['id'];

$query00 = mysqli_query($conexao,"SELECT cobranca.idcliente, cliente.id FROM cobranca
LEFT JOIN cliente ON cobranca.idcliente = cliente.id
WHERE cobranca.idcobrancaprincipal='$id' AND cobranca.situacao IN ('PENDENTE','VENCIDO')") or die (mysqli_error($conexao));
$ret00 = mysqli_fetch_array($query00);
@$idcliente = $ret00['idcliente'];
?>
<!DOCTYPE HTML>
<!-- SPACES 2 -->
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="Resource-type" content="document">
    <meta name="Robots" content="all">
    <meta name="Rating" content="general">
    <meta name="author" content="Gabriel Masson">
    <title>Carnê</title>
    <link href="img/favicon.png" rel="shortcut icon" type="image/x-icon">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <style>
            * {
          font-size: 10px;
          margin: 0;
          padding: 0;
        }

      .container{
        width: 750px;
        margin: 0px auto;
		    padding-bottom: 20px;
      }
      .esquerda{
        width: 150px;        
      }
      .direita{
        width: 585px
      }
    </style>
  </head>
  <body class="container">
  <div class="bto">
    <button class="btn-impress" onclick="window.print()">Imprimir</button>&nbsp;
    <?php echo'<a href="capa.php?id='.$idcliente.'" class="btn-impress" target="_blank">Capa do carnê</a>
  </div>';
  if (@$qtd > 212) { header("Location: index.php?error=qtd_limite"); }
$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT cobranca.*, cliente.rua,numero,bairro,municipio,estado,cpf,cnpj FROM cobranca 
LEFT JOIN cliente ON cobranca.idcliente = cliente.id
WHERE cobranca.idcobrancaprincipal='$id' AND cobranca.situacao IN ('PENDENTE','VENCIDO')") or die (mysqli_error($conexao));
$count = 1;
while ($ret = mysqli_fetch_array($query)) {
if (!$ret['cliente']) { $nome = ""; } else { $nome = strtoupper($ret['cliente']); }
if (!$ret['rua']) { $endereco = ""; } else { $endereco = AspasForm($ret['rua'].','.$ret['numero'].' - '.$ret['bairro'].' - '.$ret['municipio'].'/'.$ret['estado']); }
if ($ret['cpf'] != '') { $doccliente = $ret['cpf']; } else {  $doccliente = $ret['cnpj'];   }
if (!$ret['valor']) { $valor = ""; } else { $valor = Real($ret['valor']); }
if (!$ret['nparcela']) { $qtd = ""; } else { $qtd = addslashes($ret['nparcela']); }
if (!$ret['vencimento']) { $vence = ""; } else { $vence = dataForm($ret['vencimento']); }
if (!$ret['ncobranca']) { $ncobranca = ""; } else { $ncobranca = $ret['ncobranca']; }
if (!$ret['obs']) { $obs = ""; } else { $obs = AspasForm($ret['obs']); }
if ($ret['situacao'] == 'RECEBIDO') { $situacao = $ret['situacao']; }
$diasdesconto = date('Y-m-d', strtotime('-'.$diasdesconto0.' days', strtotime($ret['vencimento'])));
if (!$ret['datagerado']) { $datagerado = ""; } else { $datagerado = dataForm($ret['datagerado']); }

  echo '<!-- PARCELA -->
  <div class="parcela" style="font-family: sans-serif; font-size: 14px">
    <div class="grid">
      <div class="col2 esquerda">
        <div class="destaca">
          <b>MK-GESTOR | 0001</b>
          <table style="width:100%; border: 2px solid black">
            <tr>
              <td style="border-top: none">
                <small>Nosso número</small>
                <br><b>'.$ncobranca.'</b>
              </td>
            </tr>
            <tr><td style="border-top: 2px solid"><small>Vencimento</small><br><b>'.$vence.'</b></td></tr>
            <tr><td style="border-top: 2px solid"><small>Valor</small><br><b>R$ '.$valor.'</b></td></tr>
            <tr><td style="border-top: 2px solid"><small>(-) Desconto</small><br>&nbsp;</td></tr>
            <tr><td style="border-top: 2px solid"><small>(+)Multas/Juros</small><br>&nbsp;</td></tr>
            <tr><td style="border-top: 2px solid"><small>(=) Valor</small><br>&nbsp;</td></tr>
            <tr><td style="border-top: 2px solid"><small>N° documento</small><br>'.$ncobranca.'</td></tr>
            <tr><td style="border-top: 2px solid"><small>Pagador</small><br>'.$nome.'</td></tr>
          </table>
          <br>
        </div>
      </div>
      <div class="col10 direita">
        <b>MK-GESTOR | 0001 |  '.$ncobranca.'</b>
        <table style="width:100%; border: 2px solid black;">        
        <tr>
          <td colspan="5" style="border-right: 2px solid; border-top: none">
            <small>Local de pagamento</small><br>'.$nome_empresa.'
          </td>
          <td style="border-top: none">
            <small>Vencimento</small><br><b>'.$vence.'</b>
          </td>
        </tr>
        <tr>
          <td colspan="6" style="border-top: 2px solid">
            <small>Beneficiário</small><br>'.$nome_empresa.'  /  '.$docempresa.'
          </td>
        </tr>
        <tr>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>Data</small><br>'.$datagerado.'</td>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>N° documento</small><br>'.$ncobranca.'</td>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>Espécie</small><br>DM</td>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>Aceite</small><br>Não</td>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>Data proc.</small><br>'.$datagerado.'</td>
        <td colspan="5" style="border-top: 2px solid">
          <small>Nosso número</small><br>'.$ncobranca.'
        </td>
        </tr>
        <tr>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>Uso da empresa</small><br>&nbsp;</td>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>Carteira</small><br>0001</td>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>Espécie</small><br>R$</td>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>Qtde Moeda</small><br>&nbsp;</td>
          <td style="border-right: 2px solid; border-top: 2px solid"><small>(x) Valor</small><br></td>
        <td colspan="4" style="border-top: 2px solid">
          <small>(=) Valor</small><br><b>R$ '.$valor.'</b>
        </td>
        </tr>
        <tr>
        <td colspan="5" rowspan="4" style="border-right: 2px solid; border-top: 2px solid; font-size: 18px !important">
          <small>Instruções - Texto de responsabilidade do beneficiário</small>
          <p>Parcela '.$count.' de '.@$qtd.' 
          <br> 
          Não receber após
          <br><br>
          Após vencimento não cobrar multa/juros
          <br>
          Até '.dataForm($diasdesconto).' conceder desconto de R$ '.$valordesconto.'
          <br>
          <b>Não receber pagamento em cheque</b>
          </p>
          <hr style="background: #000; margin-top: 10px">
          <p>
          <b>
          Cliente: '.$nome.'<br />
          Documento cliente: '.$doccliente.'<br />
          Endereço:'.$endereco.'
          </b>
          </p>            
        </td>      
        <td style="border-top: 2px solid">
          <small>(-) Desconto</small><br>&nbsp;
        </td>
        </tr>      
        <tr>       
        <td style="border-top: 2px solid; WIDTH: 80PX">
          <small>(+)Multas/Juros</small><br>&nbsp;
        </td>
        </tr>       
        <tr>       
        <td style="border-top: 2px solid">
          <small>(=) Valor</small><br>&nbsp;
        </td>
        </tr>       
        </table>
      </div>
    </div>
  </div>';
  $count++;
}
?>
  </body>
</html>