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
if(!empty(@$_GET['id'])){
@$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM recibosavulso WHERE id='$id'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
echo'
    <input type="text" style="display:none" name="id" value="'.$dd['id'].'"/>
    <input type="text" style="display:none" name="nrecibo" value="'.$dd['nrecibo'].'"/>

    <label class="col-lg-12">Nome<small class="text-red">*obrigatório</small>
        <input type="text" class="form-control" placeholder="Nome" name="nome" value="'.AspasForm($dd['nome']).'" required/>
    </label>
    <label class="col-lg-12">CPF/CNPJ <small class="text-red">*obrigatório</small>
        <input type="number" class="form-control" placeholder="Apenas números" name="cpf_cnpj" value="'.$dd['cpf_cnpj'].'" required/>
    </label>
    <label class="col-lg-12">Referente/Descrição
        <textarea rows="3" class="form-control" placeholder="Refente a:" name="descricao">'.AspasForm($dd['descricao']).'</textarea>
    </label>
    <label class="col-lg-12">Valor
        <input type="text" class="form-control real" placeholder="Valor" name="valor" value="'.Real($dd['valor']).'"/>
    </label>
    <label class="col-lg-12">Data
        <input type="text" class="form-control" placeholder="Data" name="data" value="'.dataForm($dd['data']).'"/>
    </label>
';
}
?>
<!-- mascaras -->
<script src="dist/js/jquery.mask.js"></script>
<script src="dist/js/jquery.maskMoney.js"></script>
<script src="dist/js/meusscripts.js"></script>