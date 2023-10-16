<?php 
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
@$id = $_GET['id'];
if(!empty($id)){
    mysqli_query($conexao,"DELETE FROM usuarios WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM user WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM caixa WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM cliente WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM contratos WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM alertas WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM cobranca WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM dadoscobranca WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM historico WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM notas WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM servidor WHERE idempresa='$id'");
    //mysqli_query($conexao,"DELETE FROM sms WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM token_cli WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM recibosavulso WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM plano WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM permissao WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM log_cobranca WHERE idempresa='$id'");
    //mysqli_query($conexao,"DELETE FROM log_chamado WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM j_gastos WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM j_fornecedor_equip WHERE idempresa='$id'");
    mysqli_query($conexao,"DELETE FROM j_estoque_saida WHERE idempresa='$id'");
    echo delete();
}else{
    echo persona('Algo deu errado :( !');
}
?>            