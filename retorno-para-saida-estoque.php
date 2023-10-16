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

$id = @$_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM j_estoque WHERE id='$id'") or die (mysqli_error($conexao));
$ret = mysqli_fetch_array($query);
echo'
<div class="row">
    <input type="text" name="id" value="'.$ret['id'].'"/>
    <div class="col-lg-12">

        <label class="col-xs-12 col-lg-6 col-md-12 col-sm-12">Colaborador retirando
        <select type="text" class="form-control" name="usuariosaida" required>
        <option value="">selecione</option>';
        $query1 = mysqli_query($conexao,"SELECT * FROM usuarios WHERE situacao=1 ORDER BY nome ASC") or die (mysqli_error($conexao));
            while($ret1 = mysqli_fetch_array($query1)){
                echo'<option value="'.$ret1['nome'].'">'.$ret1['nome'].'</option>';
            }echo'    
        </select>
        </label>

        <label class="col-xs-12 col-lg-6 col-md-12 col-sm-12">Quantidade
        <input type="number" class="form-control" placeholder="Quantidade" name="quantidade"/>
        </label>

    </div>

</div>';

?>