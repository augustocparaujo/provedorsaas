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

$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM plano WHERE id='$id'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
echo'
    <input type="text" style="display:none" name="id" value="'.$dd['id'].'"/>
    <label class="col-lg-12"> Servidor
    <select type="text" class="form-control" name="servidor" required>';
    if($dd['servidor'] != 0){ echo '<option value="'.$dd['servidor'].'">'.$dd['nomeservidor'].'</option>';}else{
        echo'<option value="">sem servidor</option>';
    }
    $querys = mysqli_query($conexao,"SELECT * FROM servidor WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
    if(mysqli_num_rows($querys) >= 1){
    while($dds = mysqli_fetch_array($querys)){
        echo'<option value="'.$dds['id'].'">'.$dds['nome'].'</option>';

    }}else{
        echo'<option value="">sem servidor</option>';
    }
    echo'                
    </select>                
</label>
    <label class="col-lg-12">Nome
        <input type="text" class="form-control" placeholder="Nome" name="plano" value="'.$dd['plano'].'" required/>
    </label>
    <label class="col-lg-12">Endereço local
        <input type="text" class="form-control" name="enderecolocal" placeholder="Ex: 172.16.0.1" value="'.$dd['enderecolocal'].'"/>
    </label>
    <label class="col-lg-12">Endereço remoto
        <input type="text" class="form-control" name="enderecoremoto" placeholder="Ex:PPOE-CLIENTTES" value="'.$dd['enderecoremoto'].'"/>
    </label>
    <label class="col-lg-12">Velocidade (Donwload/Upload)
        <input type="text" class="form-control" name="velocidade" placeholder="Ex:20M/20M" value="'.AspasForm($dd['velocidade']).'"/>
    </label>    
    <label class="col-lg-12">Valor
        <input type="text" class="form-control real" placeholder="Valor" name="valor" value="'.Real($dd['valor']).'" required/>
    </label>
';
?>
<!-- mascaras -->
<script src="dist/js/jquery.mask.js"></script>
<script src="dist/js/jquery.maskMoney.js"></script>
<script src="dist/js/meusscripts.js"></script>