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

$id = $_POST['id'];
@$servidor = $_POST['servidor'];
//buscar nome servidor
$querys = mysqli_query($conexao,"SELECT * FROM servidor WHERE id='$servidor'") or die (mysqli_error($conexao));
$dds = mysqli_fetch_array($querys);
$nomeservidor = $dds['nome'];
$ipservidor = $dds['ip'];
$user = $dds['user'];
$passwords = $dds['password'];

//plano antigo
$queryss = mysqli_query($conexao,"SELECT * FROM plano WHERE id='$id'") or die (mysqli_error($conexao));
$ddss = mysqli_fetch_array($queryss);
$antigoplano = AspasBanco($ddss['plano']);

$plano = AspasBanco($_POST['plano']);
$enderecoLocal = AspasBanco($_POST['enderecolocal']);
$enderecoRemoto = AspasBanco($_POST['enderecoremoto']);
$velocidade = AspasBanco($_POST['velocidade']);
$valor = Moeda($_POST['valor']);

if(!empty($id) || !empty($valor)){

        //alterar plano mk
            require_once('routeros_api.class.php');
            $mk = new RouterosAPI();  
            if($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
                //alterar usuário mk
                $find = @$mk->comm("/ppp/profile/print", array("?name" =>  utf8_decode($antigoplano),));                
                //existe
                if (count($find) >= 1) {
                    $Finduser  = $find[0];
                    $find = $mk->comm("/ppp/profile/set", array(
                        ".id" =>  $Finduser['.id'],
                        "name" => utf8_decode($plano),
                        "rate-limit"=>utf8_decode($velocidade)
                    ));
                }

                mysqli_query($conexao,"UPDATE plano SET
                servidor='$servidor',
                nomeservidor='$nomeservidor',
                plano='$plano',
                enderecolocal='$enderecoLocal',
                enderecoremoto='$enderecoRemoto',
                velocidade='$velocidade',
                valor='$valor' WHERE id='$id'") or die (mysqli_error($conexao));

                echo update();

            }                
}else{
    echo persona('Campos obrigatórios.');
}

?>