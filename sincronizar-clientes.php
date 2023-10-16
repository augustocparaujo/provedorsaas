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

$query = mysqli_query($conexao,"SELECT * FROM servidor WHERE id='$idservidor'") or die (mysqli_error($conexao));;
$ret = mysqli_fetch_array($query);
$idempresa = $ret['idempresa'];
$nomeservidor = $ret['nome'];
$ip = $ret['ip'];
$user = $ret['user'];
$password = $ret['password'];

//se houver session mikrotik ativa
if(!empty($idservidor)){
  require_once('routeros_api.class.php');
  $mk = new RouterosAPI();

  if($mk->connect($ip, decrypt($user), decrypt($password))) {
    $find = $mk->comm("/ppp/secret/print");  
    if (count($find) >= 1) {
      foreach ($find as $key => $value) {
        $login = $find[$key]['name'];
        $senha = $find[$key]['password'];
        $profile0 = AspasBanco($find[$key]['profile']);
        $service = $find[$key]['service'];

        //pegar id do plano antes dos clientes
        $query1 = mysqli_query($conexao,"SELECT * FROM plano WHERE plano='$profile0' AND idempresa='$idempresa'") or die (mysqli_error($conexao));
        $ret1 = mysqli_fetch_array($query1);
        @$profile = $ret1['id'];
        @$nomeplano = $ret1['plano'];

          //select nos clientes pra ver se já existe
          $query = mysqli_query($conexao,"SELECT * FROM contratos WHERE login='$login' AND idempresa='$idempresa'");          
          if(mysqli_num_rows($query) == 0){             
            mysqli_query($conexao,"INSERT INTO contratos (idcliente,idempresa,login,service,senha,plano,nomeplano) 
            VALUES (0,'$idempresa','$login','$service','$senha','$profile','$nomeplano')") 
            or die (mysqli_error($conexao)); 
          }

      }
    }  
    echo persona('Sincroniado com sucesso');     
  }else {
    echo persona("Falha na conexão");
  }
}

?>