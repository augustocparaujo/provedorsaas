<?php
if (!empty($_POST['id'])) {
    include_once('conexao.php');
    include_once('funcoes.php');
    @$id = limpa($_POST['id']);

    $query = mysqli_query($conexao, "SELECT cobranca.*, dadoscobranca.tokenmercado FROM cobranca 
    INNER JOIN dadoscobranca ON dadoscobranca.idempresa = cobranca.idempresa
    WHERE cobranca.id='$id' AND dadoscobranca.banco='Mercadopago'") or die(mysqli_error($conexao));
    $ret = mysqli_fetch_array($query);
    $token = $ret['tokenmercado'];

    require_once('api_mercadopago.php');
    gerarPix($id, $ret['valor'], AspasBanco($ret['descricao']), AspasBanco($token));

    sleep(4);
}
