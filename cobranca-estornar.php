<?php
session_start();
include('conexao.php'); 
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM cobranca WHERE id='$id'") or die (mysqli_error($conexao));
$ret = mysqli_fetch_array($query);
$idcliente = $ret['idcliente'];
$parcela = 1;
$vencimento = $ret['vencimento'];
$valor = $ret['valor'];
$obs = $ret['obs'];
$titulo = $ret['ncobranca'];

//achar em caixa pelo titulo 00034436831672616416 e colocar tipo:Cancelado
//require_once('api_carteira.php');
//gerarCobranca($idcliente,$parcela,$vencimento,$valor,$obs,$idempresa);

if($ret['banco'] == 'Banco Juno'){

    //cobrança cancelada, caixa:cancelado
    include('api_juno.php');
    cancelarCobranca($id);   

}elseif($ret['banco'] == 'Gerencianet'){

    //cobrança cancelada, caixa:cancelado
    include('api_gerencianet.php');
    cancelarCobranca($id);

}elseif($ret['banco'] == 'Banco do Brasil'){ 

    //cobrança cancelada, caixa:cancelado 
    include('api_bb.php'); 
    cancelarCobranca($id,$nomeuser);

}elseif($ret['banco'] == 'Carteira'){

    //gerar cobrança carteira: cobrança cancelada, caixa:cancelado
    include('api_carteira.php');
    cancelarCobranca($id,$titulo);

}else{
    echo'<div id="toast-container" class="toast-top-right">
    <div class="toast toast-error" style="">
        <button class="toast-close-button">×</button>
        <div class="toast-title">Erro inesperado!</div>
    </div>
    </div>';
}



//gerar cobranca


    @$obs = AspasBanco($obs);
    $query = mysqli_query($conexao,"SELECT * FROM cliente WHERE id='$idcliente'") or die (mysqli_error($conexao));
    $dd = mysqli_fetch_array($query);
    
    @$idcobrancaprincipal = $id.$codigocobranca;

    for ($i = 1; $i <= $parcela; $i++) {
        if($i == 1 OR $parcela == 0){
            $codigocobranca = $id.date('Yms'); 
        }else{
            $query0 = mysqli_query($conexao,"SELECT * FROM cobranca WHERE idcliente='$idcliente' ORDER BY id DESC LIMIT 1") or die (mysqli_error($conexao));
            $reto = mysqli_fetch_array($query0);
            $vencimento = $reto['vencimento'];
            //@$vencimento = date($vencimento,strtotime(+1,'Month'));                
            $vencimento = date('Y-m-d', strtotime('+1 month', strtotime($vencimento)));
            $codigocobranca = ($id.$reto['id']) + 2;
        } 

        $nomecliente = $dd['nome'];
        $emailcliente = $dd['email'];
        $situacao = 'PENDENTE';
        mysqli_query($conexao,"INSERT INTO cobranca (banco,idempresa,idcliente,tipo,tipocobranca,idcobrancaprincipal,parcela,ncobranca,cliente,vencimento,valor,situacao,obs,datagerado) 
        VALUES ('Carteira','$idempresa','$idcliente','CARTEIRA','Plano','$idcobrancaprincipal','$i','$codigocobranca','$nomecliente','$vencimento','$valor','PENDENTE','$obs',NOW())") 
        or die (mysqli_error($conexao));
    }
    $i = $i - 1;
    mysqli_query($conexao,"UPDATE cobranca SET nparcela='$i' WHERE idcobrancaprincipal='$idcobrancaprincipal'") or die(mysqli_error($conexao));  

    echo persona('Gerado com sucesso');

?>