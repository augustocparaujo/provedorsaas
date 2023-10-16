<?php 
@session_start();
include('conexao.php'); 
@$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

function AspasBancoNovo($string){
	$string = str_replace(chr(146).chr(146),'"', $string);
	$string = str_replace(chr(146),"'",$string);
	return addslashes($string);
};

$id = $_POST['id'];
$obs = AspasBancoNovo($_POST['obs']);
if($_POST['obs'] != ''){
    $sql = mysqli_query($conexao,"SELECT * FROM cobranca WHERE id='$id'") or die (mysqli_error($conexao));
    $d = mysqli_fetch_array($sql);
    //justificativa
    mysqli_query($conexao,"UPDATE cobranca SET obs='$obs' WHERE id='$id'") or die (mysqli_error($conexao));

    //verificar pra qual api mandar
    if($d['banco'] == 'Banco Juno'){
        include('api_juno.php');
        //passa valores para função
        cancelarCobranca($id);

    }elseif($d['banco'] == 'Gerencianet'){
        
        include('api_gerencianet.php');
        cancelarCobranca($id);

    }elseif($d['banco'] == 'Banco do Brasil'){

        include('api_bb.php');
        cancelarCobranca($id,$nomeuser);

    }elseif($d['banco'] == 'Carteira'){

        include('funcoes.php');
        mysqli_query($conexao,"UPDATE cobranca SET situacao='CANCELADO',usuarioatualizou='$nomeuser',atualizado=NOW() WHERE id='$id'") or die (mysqli_error($conexao));
        echo persona('Cancelado com sucesso!');

    }else{
        include('funcoes.php');
        echo deletePersona('Erro inesperado1 !');
    }
}else{
    include('funcoes.php');
    echo deletePersona('Erro inesperado2!');
}

?>             