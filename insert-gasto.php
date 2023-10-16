<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }

if($_POST['novacategoria'] != ''){ @$categoria = AspasBanco($_POST['novacategoria']); }
else{ @$categoria = AspasBanco($_POST['categoria']); }
@$tipo = $_POST['tipo'];
$vencimento = dataBanco($_POST['vencimento']);
@$descricao = AspasBanco($_POST['descricao']);
@$valor = Moeda($_POST['valor']);

if(!empty($categoria) || !empty($tipo)){
    mysqli_query($conexao,"INSERT INTO j_gastos
    (idempresa,categoria,tipo,descricao,vencimento,valor,data)
    VALUES ('$idempresa','$categoria','$tipo','$descricao','$vencimento','$valor',NOW())") or die (mysqli_error($conexao));

    echo insert();    
}else{
    echo persona('Erro inesperado!');
}

?>