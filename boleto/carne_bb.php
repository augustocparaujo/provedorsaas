<?php
include("include/funcoes_bb.php");
include('../conexao.php');
include('../funcoes.php');
$id = $_GET['id'];

$query00 = mysqli_query($conexao, "SELECT cobranca.*,cliente.cpf,cnpj,cep,rua,numero,bairro,municipio,estado FROM cobranca
LEFT JOIN cliente ON cobranca.idcliente = cliente.id
WHERE cobranca.idcliente='$id' AND cobranca.banco='Banco do Brasil' AND cobranca.situacao IN ('PENDENTE','VENCIDO')") or die(mysqli_error($conexao));
while ($ret = mysqli_fetch_array($query00)) {
	$nparcela = $ret['nparcela'];
	$parcela = $ret['parcela'];
	$idcliente = $ret['idcliente'];
	$idempresa = $ret['idempresa'];
	$sacado = $ret['cliente'];

	#######################################GERAR QRCODE IMAGE
	$filename = '../imgqrcode/' . $ret['ncobranca'] . '.png';
	if (file_exists($filename)) {
		$qr = '<img src="' . $filename . '" width="90%"/>';
	} else {
		include_once('../phpqrcode/gerarQr.php');
		qrCode($ret['qrcode2'], $ret['idcobranca']);
		$qr = '<img src="' . $filename . '" width="90%"/>';
	}

	if ($ret['cnpj'] != '') {
		$doccliente = $ret['cnpj'];
	} else {
		$doccliente = $ret['cpf'];
	}
	$endereco = 'Endereço: ' . $ret['rua'] . ',' . $ret['numero'] . ' / Bairro: ' . $ret['bairro'] . ' | Cidade: ' . $ret['municipio'] . ' / UF: ' . $ret['estado'] . ' CEP: ' . $ret['cep'];
	//dados cobranca empresa
	$queryc = mysqli_query($conexao, "SELECT dadoscobranca.*,user.nome,user.fantasia,user.cpf_cnpj,user.email,user.contato,user.rua,user.bairro,user.cidade,user.estado
FROM dadoscobranca 
LEFT JOIN user ON dadoscobranca.idempresa = user.idempresa
WHERE dadoscobranca.idempresa='$idempresa' AND dadoscobranca.recebercom='Banco do Brasil'") or die(mysqli_error($conexao));
	$rete = mysqli_fetch_array($queryc);
	$diasdesconto0 = $rete['diasdesconto'];
	$valordesconto = Real($rete['valordesconto']);
	if (!$rete['nome']) {
		$nome_empresa = strtoupper($rete['nome']);
	} else {
		$nome_empresa = strtoupper($rete['fantasia']);
	}
	if (!$rete['rua']) {
		$endereco_empresa = "";
	} else {
		$endereco_empresa = addslashes($rete['rua'] . ',' . $rete['numero'] . ' - Bairro: ' . $rete['bairro'] . ' - Cidade:' . $rete['cidade'] . '/' . $rete['estado']);
	}
	if (!$rete['contato']) {
		$tel_empresa = "";
	} else {
		$tel_empresa = addslashes($rete['contato']);
	}
	if (!@$logomarcauser) {
		$logo = "";
	}
	if (!$rete['cpf_cnpj']) {
		$docempresa = "";
	} else {
		$docempresa = $rete['cpf_cnpj'];
	}
	if (!$rete['contrato']) {
		$contrato = $rete['contrato'];
	}
	if (!$rete['agencia']) {
		$agencia = $rete['agencia'];
	}
	if (!$rete['codigocedente']) {
		$cedente = $rete['codigocedente'];
	}
	if (!$rete['conta']) {
		$conta = $rete['conta'];
	}
	$hoje = date("d/m/Y");

	// INFORMACOES PARA O CLIENTE
	$demonstrativo1 = $ret['descricao'];
	$demonstrativo2 = "";

	// INSTRU��ES PARA O CAIXA
	$instrucoes1 = "- Sr. Caixa, cobrar multa de R$" . Real(@$rete['multaapos']) . " após o vencimento";
	$instrucoes2 = "- Receber até 90 dias após o vencimento";
	$instrucoes3 = "- Em caso de dúvidas entre em contato conosco: " . $rete['contato'] . " | E-mail:" . $rete['email'];

?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html>

	<head>
		<title>titulo</title>
		<meta http-equiv=Content-Type content=text/html charset=ISO-8859-1>
		<link rel="stylesheet" href="style_carne.css" />
	</head>

	<body>
		<div id="container">

			<div id="esquerda" style="display:inline-block">

				<table style="border-right: 1px solid black;">
					<tr>
						<td><img src="imagens/logobb.jpg" style="width:145px !important; height: 30px"></td>
					</tr>
				</table>

				<table class="line" cellspacing="0" cellpadding="0" style="border-right: 1px solid black">
					<tbody>
						<tr class="titulos">
							<td class="vencimento2">Parcela</td>
							<td class="vencimento2">Vencimento</td>
						</tr>
						<tr class="campos">
							<td class="vencimento2"><?php echo $ret["parcela"] . '/' . $ret["nparcela"]; ?></td>
							<td class="vencimento2"><?php echo dataForm($ret["vencimento"]); ?></td>
						</tr>
					</tbody>
				</table>

				<table class="line" cellspacing="0" cellpadding="0" style="border-right: 1px solid black">
					<tbody>
						<tr class="titulos">
							<td class="vencimento2">Beneficiário</td>
						</tr>
						<tr class="campos">
							<td class="vencimento2"><?php echo $nome_empresa; ?></td>
						</tr>
					</tbody>
				</table>

				<table class="line" cellspacing="0" cellpadding="0" style="border-right: 1px solid black">
					<tbody>
						<tr class="titulos">
							<td class="vencimento2">CPF/CNPJ</td>
						</tr>
						<tr class="campos">
							<td class="vencimento2"><?php echo $docempresa; ?></td>
						</tr>
					</tbody>
				</table>
				<table class="line" cellspacing="0" cellpadding="0" style="border-right: 1px solid black">
					<tbody>
						<tr class="titulos">
							<td class="vencimento2">(=)Valor do documento</td>
						</tr>
						<tr class="campos">
							<td class="vencimento2"><?php echo Real($ret["valor"]); ?></td>
						</tr>
					</tbody>
				</table>

				<table class="line" cellspacing="0" cellpadding="0" style="border-right: 1px solid black">
					<tbody>
						<tr class="campos">
							<td><?php echo $qr ?></td>
						</tr </tbody>
				</table>

				<table class="line" cellspacing="0" cellpadding="0" style="border-right: 1px solid black">
					<tbody>
						<tr class="titulos">
							<td class="vencimento2">Número documento</td>
						</tr>
						<tr class="campos">
							<td class="vencimento2"><?php echo $ret["code"]; ?></td>
						</tr>
					</tbody>
				</table>

				<table class="line" cellspacing="0" cellpadding="0" style="border-right: 1px solid black">
					<tbody>
						<tr class="titulos">
							<td class="vencimento2">Pagador</td>
						</tr>
						<tr class="campos">
							<td class="vencimento2"><?php echo $sacado . '<br /> CPF/CNPJ:' . $doccliente; ?></td>
						</tr>
					</tbody>
				</table>

				<!--direita -->


			</div>

			<div id="direita" style="display:inline-block">
				<div id="boleto">



					<table class="header" border=0 cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td width=50>
									<div class="field_cod_banco">001-9</div>
								</td>
								<td class="linha_digitavel"><?php echo $ret["codigodelinhadigitavel"] ?></td>
							</tr>
						</tbody>
					</table>

					<table class="line" cellspacing="0" cellpadding="0">
						<tbody>
							<tr class="titulos">
								<td class="local_pagto">Local de pagamento</td>
								<td class="vencimento2">Vencimento</td>
							</tr>
							<tr class="campos">
								<td class="local_pagto">QUALQUER BANCO AT&Eacute; O VENCIMENTO</td>
								<td class="vencimento2"><b><?php echo dataForm($ret["vencimento"]); ?></b></td>
							</tr>
						</tbody>
					</table>


					<table class="line" cellspacing="0" cellpadding="0">
						<tbody>
							<tr class="titulos">
								<td class="cedente2">Beneficiário</td>
								<td class="ag_cod_cedente2">Ag&ecirc;ncia/C&oacute;digo cedente</td>
							</tr>
							<tr class="campos">
								<td class="cedente2"><?php echo $nome_empresa; ?></td>
								<td class="ag_cod_cedente2"><?php echo $rete["agencia"] . '/' . $rete['codigocedente']; ?></td>
							</tr>
						</tbody>
					</table>

					<table class="line" cellspacing="0" cellpadding="0">
						<tbody>
							<tr class="titulos">
								<td class="data_doc">Data do documento</td>
								<td class="num_doc2">No. documento</td>
								<td class="especie_doc">Espécie doc.</td>
								<td class="aceite">Aceite</td>
								<td class="data_process">Data process.</td>
								<td class="nosso_numero2">Nosso n&uacute;mero</td>
							</tr>
							<tr class="campos">
								<td class="data_doc"><?php echo dataForm($ret["datagerado"]); ?></td>
								<td class="num_doc2"><?php echo $ret["code"]; ?></td>
								<td class="especie_doc">DM</td>
								<td class="aceite"></td>
								<td class="data_process"><?php echo dataForm($ret["datagerado"]); ?></td>
								<td class="nosso_numero2"><?php echo $ret["ncobranca"]; ?></td>
							</tr>
						</tbody>
					</table>

					<table class="line" cellspacing="0" cellPadding="0">
						<tbody>
							<tr class="titulos">
								<td class="reservado">Uso do banco</td>
								<td class="carteira">Carteira</td>
								<td class="especie2">Espécie</td>
								<td class="qtd2">Quantidade</td>
								<td class="xvalor">x Valor</td>
								<td class="valor_doc2">(=) Valor documento</td>
							</tr>
							<tr class="campos">
								<td class="reservado">&nbsp;</td>
								<td class="carteira"><?php echo @$carteira; ?></td>
								<td class="especie2">R$</td>
								<td class="qtd2"><?php echo $ret["parcela"] . '/' . $ret["nparcela"]; ?></td>
								<td class="xvalor"><?php echo @$valor_unitario; ?></td>
								<td class="valor_doc2"><?php echo Real($ret["valor"]); ?></td>
							</tr>
						</tbody>
					</table>


					<table class="line" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td class="last_line" rowspan="6">
									<table class="line" cellspacing="0" cellpadding="0">
										<tbody>
											<tr class="titulos">
												<td class="instrucoes">
													Instru&ccedil;&otilde;es (Texto de responsabilidade do cedente)
												</td>
											</tr>
											<tr class="campos">
												<td class="instrucoes" rowspan="5">
													<p><?php echo @$demonstrativo1; ?></p>
													<p><?php echo @$instrucoes1; ?></p>
													<p><?php echo @$instrucoes2; ?></p>
													<p><?php echo @$instrucoes3; ?></p><br />
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

							<tr>
								<td>
									<table class="line" cellspacing="0" cellpadding="0">
										<tbody>
											<tr class="titulos">
												<td class="desconto2">(-) Desconto / Abatimento</td>
											</tr>
											<tr class="campos">
												<td class="desconto2">&nbsp;</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>


							<tr>
								<td>
									<table class="line" cellspacing="0" cellpadding="0">
										<tbody>
											<tr class="titulos">
												<td class="mora_multa2">(+) Mora / Multa</td>
											</tr>
											<tr class="campos">
												<td class="mora_multa2">&nbsp;</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

							<tr>
								<td class="last_line">
									<table class="line" cellspacing="0" cellpadding="0">
										<tbody>
											<tr class="titulos">
												<td class="valor_cobrado2">(=) Valor cobrado</td>
											</tr>
											<tr class="campos">
												<td class="valor_cobrado2">&nbsp;</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>


					<table class="line" cellspacing="0" cellPadding="0">
						<tbody>
							<tr class="titulos">
								<td class="sacado2">Pagador</td>
							</tr>
							<tr class="campos">
								<td class="sacado2">
									<p><?php echo @$sacado; ?> | CPF/CNPJ: <?php echo $doccliente; ?></p>
									<p><?php echo @$endereco; ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="barcode">
						<p>
							<?php fbarcode($ret["codigobarra"]); ?>
						</p>
					</div>
					<div class="cut">
						<p>Corte na linha pontilhada</p>
					</div>

				</div>


			</div>
		</div>

	<?php
}
	?>
	</body>

	</html>