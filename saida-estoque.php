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

$id = @$_POST['id'];
$quantidade = $_POST['quantidade'];
$usuariosaida = AspasBanco($_POST['usuariosaida']);
//idempresa	iditem	quantidade	usuariocad	data	usuariosaida	datasaida	

if(@$id == '' || !empty($_POST['usuariosaida'])){
    //verficar quantidade no estoque
    $query = mysqli_query($conexao,"SELECT * FROM j_estoque WHERE id='$id'") or die (mysqli_error($conexao));    
    $ret = mysqli_fetch_array($query);
    //calculo agora
    if($ret['quantidade'] == 0){ 
        echo persona('Sem estoque!');
    }elseif($quantidade > $ret['quantidade']){
        echo persona('Estoque insuficiente');
    }else{
        //saída do estoque
        $nova = $ret['quantidade'] - $quantidade;
        mysqli_query($conexao,"UPDATE j_estoque SET quantidade='$nova' WHERE id='$id'") or die (mysqli_error($conexao));
        //registro de saída
        mysqli_query($conexao,"INSERT INTO j_estoque_saida
        (idempresa,iditem,quantidade,usuariocad,data,usuariosaida,datasaida)
        VALUES ('$idempresa','$id','$quantidade','$nomeuser',NOW(),'$usuariosaida',NOW())") or die (mysqli_error($conexao));

        echo insert();
    }   
    
}else{
    echo persona('Erro inesperado!');
}

?>