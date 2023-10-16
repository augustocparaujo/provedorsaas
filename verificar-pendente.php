<?php
echo'Ao final ira aparecer todos os clientes verificados pendentes para vencidos<br /> <i style="color:red">Aguarde realizando processo...</i><hr>';
set_time_limit(0);
ob_start();
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$iduser = $_SESSION['iduser'];
if(isset($_SESSION['iduser'])!=true){echo '<script>location.href="sair.php";</script>'; }

$hoje = date('Y-m-d');
$inicio = date('Y-m-01');
$fim = date('Y-m-t');
$query0 = mysqli_query($conexao,"SELECT * FROM cobranca WHERE idempresa='$idempresa' AND valorpago='0.00' AND situacao <> 'CANCELADO' AND banco <> 'ISENTO' AND vencimento BETWEEN '$inicio' AND '$fim' AND situacao='PENDENTE' ORDER BY cliente ASC") or die (mysqli_error($conexao));
$rows = mysqli_num_rows($query0);
    while($dd = mysqli_fetch_array($query0)){ 
        $id = $dd['id'];
        if($hoje > $dd['vencimento']){
        mysqli_query($conexao,"UPDATE cobranca SET situacao='VENCIDO',usuarioatualizou='Sistema',atualizado=NOW() WHERE id='$id' AND valorpago='0.00'")
        or die (mysqli_error($conexao));
        
        echo 'Título: '.$dd['code'].'/ Cliente: '.$dd['cliente'].' / Valor: '.Real($dd['valor']).' / Vencido: '.dataForm($dd['vencimento']).'<br />';
        }
        if($hoje < $dd['vencimento']){
            mysqli_query($conexao,"UPDATE cobranca SET situacao='PENDENTE',usuarioatualizou='Sistema',atualizado=NOW() WHERE id='$id' AND valorpago='0.00'")
            or die (mysqli_error($conexao));
            
            }
        echo 'Título: '.$dd['code'].'/ Cliente: '.$dd['cliente'].' / Valor: '.Real($dd['valor']).' / Vencido: '.dataForm($dd['vencimento']).'<br />';


    }
    echo'<i style="color:red">Finalizado processo...</i><hr>';


?>