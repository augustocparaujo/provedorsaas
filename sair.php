<?php
session_start();
include('conexao.php');
include('funcoes.php');
$iduser = $_SESSION['iduser'];
$usuario = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina


//logs
/*
mysqli_query($conexao,"INSERT INTO logs 
(usuario, tipo, tabela, descricao, datetime, host, ip) VALUES 
('$iduser', 'Saiu', 'Usuário', 'Saiu do sistema', NOW(), '$hostname', '$ip')");
//logs
*/
session_destroy();
session_unset();
ob_end_clean();// J� podemos encerrar o buffer e limpar tudo que h� nele
echo "<script>location.href='login.php'</script>";
?>

