<?php
ob_start();
session_start();
include('conexao.php');
include('funcoes.php');
//include('verifica_isp.php');
@$idempresa = $_SESSION['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$nomeuser = $_SESSION['usuario'];
@$iduser = $_SESSION['iduser'];

if (isset($_SESSION['iduser']) != true && @$_SESSION['hash'] == true) {
    echo '<script>location.href="sair.php";</script>';
}

$id = $_GET['id'];
$query = mysqli_query($conexao, "SELECT cobranca.*, cliente.nome,contato, config_sms.* FROM cobranca
    LEFT JOIN cliente ON cobranca.idcliente = cliente.id
    LEFT JOIN config_sms ON config_sms.idempresa = cobranca.idempresa
    WHERE cobranca.id='$id' AND cliente.contato <> ''") or die(mysqli_error($conexao));

$ret = mysqli_fetch_array($query);
//montar a notificação
$notificacao = str_replace('{{nomecliente}}', $ret['nome'], $ret['texto']);
$notificacao = str_replace('{{descricao}}', AspasBanco($ret['obs']), $notificacao);
$notificacao = str_replace('{{valor}}', Real($ret['valor']), $notificacao);
$notificacao = str_replace('{{vencimento}}', dataForm($ret['vencimento']), $notificacao);
if (!empty($ret['link']) and strpos($notificacao, '{{link}}') !== false) {

    $notificacao = str_replace('{{link}}', $ret['link'], $notificacao);
} else {
    $notificacao = str_replace('{{link}}', '', $notificacao);
}

if (strpos($notificacao, '{{mercadopago}}') !== false) {
    $notificacao = str_replace('{{mercadopago}}', 'Para pagamento via pix clique no link abaixo: https://painel.mkgestor.com.br/cascata.php?id=' . $id, $notificacao);
}

include('api_douglas.php');
$status = enviaNotificacao($ret['nome'], $ret['contato'], $notificacao, $ret['token']);

if ($status == 'sucesso') {
    //salvar log
    mysqli_query($conexao, "INSERT INTO notificacao_agendada (idempresa,nome,contato,notificacao,situacao,datadisparo) 
                VALUES('$idempresa','$nome','$contato','$msg',1,NOW())") or die(mysqli_error($conexao));

    echo persona('Enviado com sucesso');
} else {
    //salvar log
    mysqli_query($conexao, "INSERT INTO notificacao_agendada (idempresa,nome,contato,notificacao,datadisparo,erro) 
            VALUES('$idempresa','$nome','$contato','$msg',NOW(),'$status')") or die(mysqli_error($conexao));

    echo persona($status);
}
