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

$id = $_GET['id'];
$sql = mysqli_query($conexao, "SELECT j_estoque_saida.*, j_estoque.descricao AS nomeitem FROM j_estoque_saida
LEFT JOIN j_estoque ON j_estoque_saida.iditem = j_estoque.id
WHERE j_estoque.idempresa='$idempresa' AND j_estoque_saida.iditem='$id' ORDER BY j_estoque_saida.usuariocad ASC") or die(mysqli_error($conexao));

if(mysqli_num_rows($sql) >= 1){
while ($dd = mysqli_fetch_array($sql)) {
        echo'<tr>
            <td>'.$dd['id'].'</td>
            <td>'.$dd['nomeitem'].'</td>
            <td>'.$dd['quantidade'].'</td>
            <td>'.$dd['usuariocad'].'</td>
            <td>'.date('d-m-Y H:m',strtotime($dd['data'])).'</td>
            <td>'.$dd['usuariosaida'].'</td>
            <td>'.date('d-m-Y H:m',strtotime($dd['datasaida'])).'</td>
        </tr>';

}}else{ echo'<tr><td colspan="8">sem registro</td></tr>';}
?>