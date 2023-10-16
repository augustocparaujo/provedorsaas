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
if(!empty($id)){

 
        //plano
        $query2 = mysqli_query($conexao,"SELECT * FROM plano WHERE idempresa='$idempresa' AND id='$id'") or die (mysqli_error($conexao));
        $ret2 = mysqli_fetch_array($query2);
        $nomeplano = $ret2['plano'];
        $servidor = $ret2['servidor'];

        if($servidor != 0){
        //servidor
        $query3 = mysqli_query($conexao,"SELECT * FROM servidor WHERE idempresa='$idempresa' AND id='$servidor'") or die (mysqli_error($conexao));
        $ret3 = mysqli_fetch_array($query3);
        $ipservidor = $ret3['ip'];
        $user = $ret3['user'];
        $password = $ret3['password'];
        

        require_once('routeros_api.class.php');
        $mk = new RouterosAPI(); 
        if($mk->connect($ipservidor, decrypt($user), decrypt($password))) {
            $find = @$mk->comm("/ppp/profile/print", array("?name" =>  utf8_decode($nomeplano),));                                        
            if (count($find) >= 1) {  
                $Find  = $find[0];
                $find = $mk->comm("/ppp/profile/remove", array(".id" =>  $Find['.id'],));                                                        
            }    
        }
        mysqli_query($conexao,"DELETE FROM plano WHERE idempresa='$idempresa' AND id='$id'") or die (mysqli_error($conexao));

        echo delete();
        }       
}else{
    echo persona('Falta informação!');
}

?>