<?php 
session_start();
include('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

@$id = $_POST['id'];
@$nome = AspasBanco($_POST['nome']);
@$cpf_cnpj = $_POST['cpf_cnpj'];
@$descricao = AspasBanco($_POST['descricao']);
@$valor = moeda($_POST['valor']);
@$data = $_POST['data'];
$datacaixa = dataBanco($data);
$hojei = date('Y-m-d');
if(@$_POST['nrecibo'] == ''){ @$nrecibo = md5($empresa.date('Ymd')); } else { $nrecibo = $_POST['nrecibo']; }

if(@$_POST['id'] == ''){
    if(!empty($_POST['nome']) AND !empty($_POST['cpf_cnpj']) AND !empty($_POST['valor'])){

        mysqli_query($conexao,"INSERT INTO recibosavulso (idempresa,nrecibo,nome,cpf_cnpj,descricao,valor,data) 
        VALUES ('$idempresa','$nrecibo','$nome','$cpf_cnpj','$descricao','$valor','$data')") or die (mysqli_error($conexao));

        mysqli_query($conexao,"INSERT INTO caixa (tipo,idempresa,nrecibo,descricao,valor,valorpago,data,user)
        VALUE ('Entrada','$idempresa','$nrecibo','$descricao','$valor','$valor','$datacaixa','$nomeuser')") or die (mysqli_error($conexao));

        echo insert();

    }else{
        echo persona('Preencher campos obrigatórios.');
    }
}else{
    if(!empty($_POST['id']) AND !empty($_POST['nome']) AND !empty($_POST['cpf_cnpj']) AND !empty($_POST['valor'])){

        mysqli_query($conexao,"UPDATE recibosavulso SET nome='$nome',cpf_cnpj='$cpf_cnpj',descricao='$descricao',
        valor='$valor',data='$data' WHERE id='$id'") or die (mysqli_error($conexao));

        mysqli_query($conexao,"UPDATE caixa SET descricao='$descricao',valor='$valor',valorpago='$valor',data='$datacaixa',datapagamento='$hojei' WHERE nrecibo='$nrecibo'") or die (mysqli_error($conexao));

        echo update();

    }else{
        echo persona('Preencher campos obrigatórios.');
    }

}
?>            