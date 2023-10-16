<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
include_once('routeros_api.class.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM servidor WHERE id='$id'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
echo'
    <input type="text" style="display:none" name="id" value="'.$dd['id'].'"/>
    <label class="col-lg-12">Nome <small class="text-red">*obrigatório</small>
        <input type="text" class="form-control" placeholder="Nome" name="nome" value="'.$dd['nome'].'" required/>
    </label>
    <label class="col-lg-12">IP:PORTA <small class="text-red">*192.168.0.1:8728</small>
        <input type="text" class="form-control" placeholder="IP" name="ip" value="'.$dd['ip'].'" required/>
    </label>
    <label class="col-lg-12">Usuário <small class="text-red">*obrigatório</small>
        <input type="text" class="form-control" placeholder="Usuário" name="usuario" required/>
    </label>
    <label class="col-lg-12">Senha <small class="text-red">*obrigatório</small>
        <input type="text" class="form-control" placeholder="Senha" name="senha" required/>
    </label>
';

?>