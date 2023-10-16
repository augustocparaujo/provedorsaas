<?php
function Moeda22($get_valor)
{
    $source = array('.', ',');
    $replace = array('', '.');
    $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
    if (empty($valor)) {
        return 0;
    } else {
        return $valor;
    } //retorna o valor formatado para gravar no banco
}; //moeda

$valor = Moeda22($_POST['valor']);

if($_POST['tipo'] == 'Boleto'){

    include('api_gerencianet_isp.php');

    gerarCobranca($_POST['id'], $_POST['nparcela'], $_POST['vencimento'], $valor, $_POST['obs']);

}elseif($_POST['tipo'] == 'Carteira'){

      //gerar cobrança carteira
      include('api_carteira_isp.php');
      gerarCobranca($_POST['id'],$_POST['nparcela'],$_POST['vencimento'],$valor,$_POST['obs']);

}

