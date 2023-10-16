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

@$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM cobranca WHERE id='$id' AND idempresa='$idempresa'") or die (mysqli_error($conexao));
if(mysqli_num_rows($query) >= 1){
$dd = mysqli_fetch_array($query);

/*
quantidade de dias para multiplicar juros
multa
juros
soma multa  juros  valor
*/
$query1 = mysqli_query($conexao,"SELECT * FROM dadoscobranca WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
$ddc = mysqli_fetch_array($query1);

$data_vencimento = $dd['vencimento'];
$data_hoje = date('Y-m-d');
/*Calcula a diferença em segundos entre as datas*/
$diferenca = strtotime($data_hoje) - strtotime($data_vencimento);

if($diferenca >= 1){
    /*Calcula a diferença em dias*/
    $dias = floor($diferenca / (60 * 60 * 24));
    $valorjurosdia = ($dd['valor'] * $ddc['jurosapos']/100);
    $valoratualizdo = ($valorjurosdia * $dias) + $ddc['multaapos'];
}else{
    $valoratualizdo = 0.00;
}

    echo'
    <input type="text" class="hidden" id="id" name="id" value="'.$id.'"/>
    <input type="text" class="hidden" name="cobranca" value="'.$dd['ncobranca'].'"/>
    <input type="text" class="hidden" name="nomecliente" value="'.$dd['cliente'].'"/>
    <input type="text" class="hidden" name="banco" value="'.$dd['banco'].'"/>

    <label class="col-lg-12 col-md-12 col-sm-12">Vencimento
        <input type="text" class="form-control data" name="vencimento" value="'.dataForm($dd['vencimento']).'" readonly/>
    </label>
    <label class="col-lg-12 col-md-12 col-sm-12">Receber (juros + multa)
        <input type="text" class="form-control real"  name="valor" value="'.Real($dd['valor']+@$valoratualizdo).'" readonly/>
    </label>
    <label class="col-lg-12 col-md-12 col-sm-12">Cartão Crédito
        <input type="text" class="form-control real"  name="cartaocredito"/>
    </label>
    <label class="col-lg-12 col-md-12 col-sm-12">Cartão débito
        <input type="text" class="form-control real"  name="cartaodebito"/>
    </label>
    <label class="col-lg-12 col-md-12 col-sm-12">PIX
        <input type="text" class="form-control real"  name="pix"/>
    </label>
    <label class="col-lg-12 col-md-12 col-sm-12">Dinheiro
        <input type="text" class="form-control real"  name="dinheiro"/>
    </label>
    <label class="col-lg-12 col-md-12 col-sm-12">Data recebido
        <input type="date" class="form-control"  name="datapagamento" required/>
    </label>';
}else{
    echo persona('Algo deu errado :( !');
}
?>
<!-- mascaras -->
<script src="dist/js/jquery.mask.js"></script>
<script src="dist/js/jquery.maskMoney.js"></script>
<script src="dist/js/meusscripts.js"></script>