<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

@$id = $_POST['id'];
@$servidor = $_POST['servidor'];
//buscar nome servidor
$querys = mysqli_query($conexao,"SELECT * FROM servidor WHERE id='$servidor'") or die (mysqli_error($conexao));
$dds = mysqli_fetch_array($querys);
$nomeservidor = $dds['nome'];
$ipservidor = $dds['ip'];
$user = $dds['user'];
$password = $dds['password'];

@$plano = $_POST['plano'];
$velocidade = $_POST['velocidade'];
@$valor = Moeda($_POST['valor']);

if(!empty($id) || !empty($plano) || !empty($valor)){
            
            require_once('routeros_api.class.php');
            $mk = new RouterosAPI();  
            if($mk->connect($ipservidor, decrypt($user), decrypt($password))) {
                    @$mk->comm("/ppp/profile/add", array(
                        "name" => utf8_decode(AspasBanco($plano)),
                        "rate-limit"=> utf8_decode(AspasBanco($velocidade))
                    ));
                }
            mysqli_query($conexao,"INSERT INTO plano (idempresa,servidor,nomeservidor,plano,velocidade,valor) 
            VALUES ('$idempresa','$servidor','$nomeservidor','$plano','$velocidade','$valor')") or die (mysqli_error($conexao));

            echo update();
            
}else{
    echo persona('Campos obrigatórios.');
}

?>