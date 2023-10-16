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

$idservidor = $_GET['id'];

$query = mysqli_query($conexao,"SELECT * FROM servidor WHERE id='$idservidor'");
$ret = mysqli_fetch_array($query);
$idempresa = $ret['idempresa'];
$nomeservidor = $ret['nome'];
$ip = $ret['ip'];
$user = $ret['user'];
$password = $ret['password'];

/*
falta criar as regras no nat e filter firewall
*/

//se houver session mikrotik ativa
if(!empty($idservidor)){
  require_once('routeros_api.class.php');
  $mk = new RouterosAPI();

  if($mk->connect($ip, decrypt($user), decrypt($password))) {
    $find = $mk->comm("/ppp/profile/print");  
    if (count($find) >= 1) {
      foreach ($find as $key => $value) {
        @$idplano= $find[$key]['id'];
        @$plano = $find[$key]['name'];
        @$velocidade = AspasForm($find[$key]['rate-limit']);
        
        echo persona('Planos na RB: '.$plano.'<br>');

        $query1 = mysqli_query($conexao,"SELECT * FROM plano WHERE servidor='$idservidor' AND plano='$plano'") or die (mysqli_error($conexao));
        
        if(mysqli_num_rows($query1) == 0){   
          echo persona('Planos sicronizados: '.$plano.'<br>');
          
          mysqli_query($conexao,"INSERT INTO plano (idempresa,servidor,nomeservidor,plano,velocidade) 
          VALUES ('$idempresa','$idservidor','$nomeservidor','$plano','$velocidade')") or die (mysqli_error($conexao));      
          
        }
      }
      @$mk->comm("/ppp/profile/add", array(
        "name" => "Bloqueados",
        "address-list" => "Bloqueados",
      ));
    }  
    echo persona('Sincroniado com sucesso');      
  }else {
    echo persona("Falha na conexão)");
  }
}
else {
  echo persona("Selecione um servidor)");
}

?>