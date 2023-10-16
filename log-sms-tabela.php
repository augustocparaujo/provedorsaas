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
if(isset($_SESSION['iduser'])!=true){echo '<script>location.href="sair.php";</script>'; }
@$inicio = dataBanco($_POST['inicio']);
@$fim = dataBanco($_POST['fim']);
if(!empty($_POST['inicio']) AND !empty($_POST['fim'])){
$query = mysqli_query($conexao,"SELECT * FROM log_sms WHERE idempresa='$idempresa' AND data BETWEEN '$inicio' AND '$fim'") or die (mysqli_error($conexao));
if(mysqli_num_rows($query) >= 1){
    $n = 1;
    while($dd = mysqli_fetch_array($query)){
        echo'
        <tr>
            <td>'.$n.'</td>
            <td>'.$dd['id'].'</td>
            <td>'.$dd['user'].'</td>
            <td>'.dataForm($dd['data']).'</td>
            <td class="celular">'.$dd['contato'].'</td>
            <td>'.AspasForm($dd['mensagem']).'</td>
        </tr>';
        $n++;
    }
}else{ echo'<tr><td colspan="5">Sem registro</td></tr>'; }
}else{echo'<tr><td colspan="5">Preencher todos os campos</td></tr>';}
