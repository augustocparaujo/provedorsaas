<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Vers�o Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo est� dispon�vel sob a Licen�a GPL dispon�vel pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Voc� deve ter recebido uma c�pia da GNU Public License junto com     |
// | esse pacote; se n�o, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colabora��es de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de Jo�o Prado Maia e Pablo Martins F. Costa				        |
// | 														                                   			  |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +--------------------------------------------------------------------------------------------------------+
// | Equipe Coordena��o Projeto BoletoPhp: <boletophp@boletophp.com.br>              		             				|
// | Desenvolvimento Boleto Banco do Brasil: Daniel William Schultz / Leandro Maniezo / Rog�rio Dias Pereira|
// +--------------------------------------------------------------------------------------------------------+


// ------------------------- DADOS DIN�MICOS DO SEU CLIENTE PARA A GERA��O DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formul�rio c/ POST, GET ou de BD (MySql,Postgre,etc)	//

include('../conexao.php');
include('../funcoes.php');
$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT cobranca.*, cliente.rua,cliente.numero,cliente.bairro,cliente.municipio,cliente.estado,cliente.cep,
user.logomarca,user.nome as nomeempresa,user.cpf_cnpj,user.rua as enderecoempresa, user.bairro as bairroempresa, user.cidade as cidadeempresa, user.estado as estadoempresa,user.contato as contatoempresa,user.email as emailempresa,
dadoscobranca.agencia,dadoscobranca.conta,dadoscobranca.convenio,dadoscobranca.contrato,dadoscobranca.carteira,dadoscobranca.variacaocarteira,dadoscobranca.aposvencimento,dadoscobranca.multaapos
FROM cobranca 
LEFT JOIN cliente ON cobranca.idcliente = cliente.id
LEFT JOIN user ON cobranca.idempresa = user.idempresa
LEFT JOIN dadoscobranca ON cobranca.idempresa = dadoscobranca.idempresa
WHERE cobranca.id='$id' AND recebercom='Banco do Brasil'") or die (mysqli_error($conexao));
$ret = mysqli_fetch_array($query);

#######################################GERAR QRCODE IMAGE

include('../phpqrcode/gerarQr.php');
qrCode($ret['qrcode2'],$ret['idcobranca']);


#######################################
// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = $ret['aposvencimento'];
$taxa_boleto = $ret['multaapos'];//2.95
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
$valor_cobrado = "2950,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = $ret['code'];
$dadosboleto["numero_documento"] = $ret['ncobranca'];	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] = dataForm($ret['vencimento']); // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = dataForm($ret['datagerado']); // Data de emiss�o do Boleto
$dadosboleto["data_processamento"] = dataForm($ret['datagerado']); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = Real($ret['valor']); 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = $ret['cliente'];
$dadosboleto["endereco1"] = $ret['rua'].', '.$ret['numero'].' - '.$ret['bairro'];
$dadosboleto["endereco2"] = $ret['municipio'].' - '.$ret['estado'].' -  CEP:'.$ret['cep'];

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = $ret['descricao'];
$dadosboleto["demonstrativo2"] = "";
$dadosboleto["demonstrativo3"] = "Emitido pelo sistema GISP - www.gisp.digital";

// INSTRU��ES PARA O CAIXA
$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de R$".Real($taxa_boleto)." após o vencimento";
$dadosboleto["instrucoes2"] = "- Receber até 90 dias após o vencimento";
$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: <br />&nbsp; &nbsp; ".$ret['contatoempresa']." | E-mail:".$ret['emailempresa'];
$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema GISP - www.gisp.digital";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "1";
$dadosboleto["valor_unitario"] = "1";
$dadosboleto["aceite"] = "N";		
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";


// ---------------------- DADOS FIXOS DE CONFIGURA��O DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - BANCO DO BRASIL
$dadosboleto["agencia"] = $ret['agencia']; // Num da agencia, sem digito
$dadosboleto["conta"] = $ret['conta']; 	// Num da conta, sem digito

// DADOS PERSONALIZADOS - BANCO DO BRASIL
$dadosboleto["convenio"] = $ret['convenio'];  // Num do conv�nio - REGRA: 6 ou 7 ou 8 d�gitos
$dadosboleto["contrato"] = $ret['contrato']; // Num do seu contrato
$dadosboleto["carteira"] = $ret['carteira'];
$dadosboleto["variacao_carteira"] = $ret['variacaocarteira'];  // Varia��o da Carteira, com tra�o (opcional)

// TIPO DO BOLETO
$dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Conv�nio c/ 8 d�gitos, 7 p/ Conv�nio c/ 7 d�gitos, ou 6 se Conv�nio c/ 6 d�gitos
$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Conv�nio c/ 6 d�gitos: informe 1 se for NossoN�mero de at� 5 d�gitos ou 2 para op��o de at� 17 d�gitos

/*
#################################################
DESENVOLVIDO PARA CARTEIRA 18

- Carteira 18 com Convenio de 8 digitos
  Nosso n�mero: pode ser at� 9 d�gitos

- Carteira 18 com Convenio de 7 digitos
  Nosso n�mero: pode ser at� 10 d�gitos

- Carteira 18 com Convenio de 6 digitos
  Nosso n�mero:
  de 1 a 99999 para op��o de at� 5 d�gitos
  de 1 a 99999999999999999 para op��o de at� 17 d�gitos

#################################################
*/


// SEUS DADOS
$dadosboleto["identificacao"] = $ret['nomeempresa'];
$dadosboleto["cpf_cnpj"] = $ret['cpf_cnpj'];
$dadosboleto["endereco"] = $ret['enderecoempresa'].'/'.$ret['bairroempresa'];
$dadosboleto["cidade_uf"] = $ret['cidadeempresa'].'/'.$ret['estadoempresa'];
$dadosboleto["cedente"] = $ret['nomeempresa'];

// N�O ALTERAR!
include("include/funcoes_bb.php"); 
include("include/layout_bb.php");
?>
