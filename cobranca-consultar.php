<?php 
include_once('conexao.php'); 
$id = $_GET['id'];
$sql = mysqli_query($conexao,"SELECT * FROM cobranca WHERE id='$id'") or die (mysqli_error($conexao));
$d = mysqli_fetch_array($sql);

//verificar pra qual api mandar
if($d['banco'] == 'Banco Juno'){
    include('api_juno.php');
    //passa valores para função
    consultarCobranca($_GET['id']);

}elseif($d['banco'] == 'Gerencianet'){
    include('api_gerencianet.php');
    consultarCobranca($_GET['id']);

}elseif($d['banco'] == 'Banco do Brasil'){
    include('api_bb.php');
    consultarCobranca($_GET['id']);

}else{
    echo' <div id="toast-container" class="toast-top-right">
    <div class="toast toast-error" style="">
        <button class="toast-close-button">×</button>
        <div class="toast-title">Erro inesperado!</div>
    </div>
</div>';
}

?>             