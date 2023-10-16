<?php
ob_start();
@session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a�o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

@$contato = limpaCPF_CNPJ($_POST['contato']);
@$msg = str_replace(' ','+',$_POST['msg']);

if ($_POST['tipo'] == 'Manual') {
  include_once 'api_smsnet.php';
  enviaSms($contato, $msg);
  
  //mysqli_query($conexao, "INSERT INTO log_sms (idempresa,contato,mensagem,user,data) VALUES ('$idempresa','$contato','$msg','$nomeuser',NOW())");
  echo persona2('Enviado com sucesso');

} elseif ($_POST['tipo'] == 'Todos') {
  $query = mysqli_query($conexao, "SELECT * FROM cliente WHERE idempresa='$idempresa' AND situacao='Ativo' AND contato <> ''");
  if (mysqli_num_rows($query) >= 1) {
    $n = 1;
    while ($ret = mysqli_fetch_array($query)) {
      $contato = $ret['contato'];
      include_once 'api_smsnet.php';
      enviaSms($contato, $msg);
      //salvar log - tabela SELECT * FROM `log_sms`
      $msg = AspasBanco($msg);
      //mysqli_query($conexao, "INSERT INTO log_sms (idempresa,contato,mensagem,user,data) VALUES ('$idempresa','$contato','$msg','$nomeuser',NOW())");
      //echo persona('SMS enviado com sucesso !');
      //sleep(2);
      $n++;
    }
    echo persona2('Enviado com sucesso: ' . $n);
  }
}