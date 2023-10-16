<?php

$query = mysqli_query($conexao,"SELECT id FROM chamado WHERE idempresa='$_SESSION[idempresa]' AND situacao IN ('ABERTO','PENDENTE')");
$totChamados = mysqli_num_rows($query);

$query0 = mysqli_query($conexao,"SELECT id FROM cliente WHERE idempresa='$_SESSION[idempresa]'") or die (mysqli_error($conexao));
$totClientes = mysqli_num_rows($query0);

$query1 = mysqli_query($conexao,"SELECT id FROM cliente WHERE idempresa='$_SESSION[idempresa]' AND situacao='Bloqueado'");
$totCancelados = mysqli_num_rows($query1);

//cobranças
@$datainicio = date('Y-m-01');
@$datafim = date('Y-m-t');
@$datafimano = date('Y-12-t');
@$datahoje = date('Y-m-d');
$sqltc = mysqli_query($conexao, "SELECT SUM(valor) AS totalcobrancas FROM cobranca WHERE idempresa='$_SESSION[idempresa]'") or die(mysqli_error($conexao));
$rett = mysqli_fetch_array($sqltc);

//cobranças abertas
$sqlta = mysqli_query($conexao, "SELECT SUM(valor) AS totalabertas FROM cobranca WHERE valorpago = 0.00 AND idempresa='$_SESSION[idempresa]' AND vencimento BETWEEN '2021-01-01' AND '$datafimano'") or die(mysqli_error($conexao));
$retabertas = mysqli_fetch_array($sqlta);

//cobranças em atraso
$sqlat = mysqli_query($conexao, "SELECT SUM(valor) AS totalatraso FROM cobranca WHERE valorpago = 0.00 AND idempresa='$_SESSION[idempresa]' AND vencimento BETWEEN '2021-01-01' AND NOW()") or die(mysqli_error($conexao));
$retatraso = mysqli_fetch_array($sqlat);

//cobranças recebidas
$sqltc = mysqli_query($conexao, "SELECT SUM(valorpago) AS totalrecebidas FROM cobranca WHERE valorpago <> 0.00 AND idempresa='$_SESSION[idempresa]'") or die(mysqli_error($conexao));
$retrecebidas = mysqli_fetch_array($sqltc);     

?>