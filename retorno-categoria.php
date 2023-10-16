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
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }


$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM j_categoria_estoque WHERE id='$id'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
echo'
    <div class="row">
    <input type="text" style="display:none" name="id" value="'.$dd['id'].'"/>
    <label class="col-lg-12">Descrição
        <input type="text" class="form-control" placeholder="Descrição" name="nome_cat" value="'.AspasForm($dd['nome_cat']).'" required/>
    </label>
    </div>  
';
?>