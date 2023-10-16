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

@$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT * FROM notas WHERE idcliente='$id' ORDER BY vencimento DESC") or die (mysqli_error($conexao));

if(mysqli_num_rows($query) >= 1){
while($dd = mysqli_fetch_array($query)){echo'
<tr>
    <td>'.AspasForm($dd['descricao']).'</td>
    <td>'.date('d-m-Y',strtotime($dd['vencimento'])).'</td>
    <td>'; if($dd['nota'] != ''){ echo'<a href="notas/'.$dd['nota'].'" class="fa fa-file-pdf-o text-black fa-2x" title="baixar nota" download></a>&emsp;'; }
        echo'</td>
    <td>       
        <a href="#" onclick="excluirNota('.$dd['id'].')" class="fa fa-trash text-red fa-2x" title="excluir nota"></a>       
    </td>
</tr>';}} else { echo'<div class="col-lg-3 text-red">Sem notas</div>';}
?>