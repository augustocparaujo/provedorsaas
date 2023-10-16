
<?php
//recebe informaçõess
//header('Access-Control-Allow-Origin: *; Content-Type: application/json;');
//mercadopago
if ($_GET['id'] != '') {
    $id = $_GET['id'];
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT * FROM dadoscobranca WHERE tokenmercado <> ''");
    $ret = mysqli_fetch_array($query);
    $token = $ret['tokenmercado'];
    if (!empty($token)) {
        require_once 'vendor/autoload.php';
        MercadoPago\SDK::setAccessToken($token);
        $payment = MercadoPago\Payment::find_by_id($id);
        $situacao = $payment->status;

        if ($situacao == 'approved') {
            mysqli_query($conexao, "UPDATE cobranca SET valor_recebido=valor,situacao='RECEBIDO',datapagamento=NOW() WHERE idcobranca='$id'") or die(mysqli_error($conexao));
            //remover notificação
            mysqli_query($conexao, "DELETE FROM notificacao_agendada WHERE idcobranca='$id'") or die(mysqli_error($conexao));
        } elseif ($situacao == 'cancelled') {
            $situacao = 'CANCELADA';
            mysqli_query($conexao, "UPDATE cobranca SET situacao='$situacao' WHERE idcobranca='$id'") or die(mysqli_error($conexao));
        } else {
        }
    }
}
