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
@$categoria = $_POST['categoria'];
@$novacategoria = AspasBanco($_POST['novacategoria']);
@$fornecedor = $_POST['fornecedor'];
@$novofornecedor = AspasBanco($_POST['novofornecedor']);
@$descricao = AspasBanco($_POST['descricao']);
@$quantidade = $_POST['quantidade'];

//categoria nova
if($_POST['categoria'] == ''){
    $query = mysqli_query($conexao,"INSERT INTO j_categoria_estoque (idempresa,nome_cat,usuariocad,data) VALUES ('$idempresa','$novacategoria','$nomeuser',NOW())")
    or die (mysqli_error($conexao));
    $categoria = mysqli_insert_id($conexao);
}
//novo fornecedor
if($_POST['fornecedor'] == ''){
    $query = mysqli_query($conexao,"INSERT INTO j_fornecedor_equip (idempresa,descricao,usuariocad,data) VALUES ('$idempresa','$novofornecedor','$nomeuser',NOW())")
    or die (mysqli_error($conexao));
    $fornecedor = mysqli_insert_id($conexao);
}


if(@$id == ''){
    mysqli_query($conexao,"INSERT INTO j_estoque
    (idempresa,categoria,fornecedor,descricao,quantidade,usuariocad,data,situacao)
    VALUES ('$idempresa','$categoria','$fornecedor','$descricao','$quantidade','$nomeuser',NOW(),'ativo')") or die (mysqli_error($conexao));

    echo insert();
    
}elseif(@$id != ''){
    mysqli_query($conexao,"UPDATE j_estoque SET 
    idempresa='$idempresa',categoria='$categoria',fornecedor='$fornecedor',
    descricao='$descricao',quantidade='$quantidade',usuariocad='$nomeuser',data=NOW() WHERE id='$id'") or die (mysqli_error($conexao));
    
    echo update();
}else{
    echo persona('Erro inesperado!');
}

?>