<?php
function Moeda22($get_valor) {
$source = array('.', ',');
$replace = array('', '.');
$valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
if(empty($valor)){return 0;}else{return $valor;} //retorna o valor formatado para gravar no banco
};//moeda
$valor = Moeda22($_POST['valor']);

//verificar pra qual api mandar

if($_POST['banco'] == 'Banco Juno'){

    include('api_juno.php');
    gerarCobranca($_POST['id'],$_POST['nparcela'],$_POST['vencimento'],$valor,$_POST['obs']);

}elseif($_POST['banco'] == 'Gerencianet'){

    include('api_gerencianet.php');
    gerarCobranca($_POST['id'],$_POST['nparcela'],$_POST['vencimento'],$valor,$_POST['obs'],$_POST['idempresa']);

}elseif($_POST['banco'] == 'Banco do Brasil'){ 

    include('api_bb.php');  
    $idcliente = $_POST['id'];
    $descricao = $_POST['obs'];
    $dataVencimento = $_POST['vencimento'];
    $idempresa = $_POST['idempresa'];
    $nparcela = $_POST['nparcela'];
    gerarCobranca($idcliente,$nparcela,$valor,$descricao,$dataVencimento,$idempresa);

}elseif($_POST['banco'] == 'Carteira'){
    //gerar cobrança carteira
    include('api_carteira.php');
    gerarCobranca($_POST['id'],$_POST['nparcela'],$_POST['vencimento'],$valor,$_POST['obs'],$_POST['idempresa']);
}else{
    echo' <div id="toast-container" class="toast-top-right">
    <div class="toast toast-error" style="">
        <button class="toast-close-button">×</button>
        <div class="toast-title">Erro inesperado!</div>
    </div>
    </div>';
}
?>  