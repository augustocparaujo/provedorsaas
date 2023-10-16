<?php
ob_start();
session_start();
include('conexao.php');
include('funcoes.php');
//include('verifica_isp.php');
@$idempresa = $_SESSION['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$nomeuser = $_SESSION['usuario'];
@$iduser = $_SESSION['iduser'];

if (isset($_SESSION['iduser']) != true && @$_SESSION['hash'] == true) {
    echo '<script>location.href="sair.php";</script>';
}

@$nome = $_POST['nome'];
@$contato = $_POST['contato'];
@$msg = AspasBanco($_POST['msg']);

if ($_POST['tipo'] == 'Manual') {

    //verifica qual api ta sendo cadastrado
    $retApi = mysqli_query($conexao, "SELECT * FROM config_sms WHERE idempresa='$idempresa' AND api='douglas'");
    if (mysqli_num_rows($retApi) > 0) {
        $r = mysqli_fetch_array($retApi);

        include_once('api_douglas.php');
        $status = enviaNotificacao($nome, $contato, $msg, $r['token']);

        if ($status == 'sucesso') {
            //salvar log
            mysqli_query($conexao, "INSERT INTO notificacao_agendada (idempresa,nome,contato,notificacao,situacao,datadisparo) 
                VALUES('$idempresa','$nome','$contato','$msg',1,NOW())") or die(mysqli_error($conexao));

            echo persona2('Enviado com sucesso', 'success', 'check-square-o');
        } else {
            //salvar log
            mysqli_query($conexao, "INSERT INTO notificacao_agendada (idempresa,nome,contato,notificacao,datadisparo,erro) 
            VALUES('$idempresa','$nome','$contato','$msg',NOW(),'algo deu errado')") or die(mysqli_error($conexao));

            echo persona2('Algo deu errado', 'danger', 'fa-exclamation-triangle');
        }
    } else {
        echo persona2('Cadastrar API', 'danger', 'fa-exclamation-triangle');
    }
} elseif ($_POST['tipo'] == 'Todos') {
    $data = dataBanco($_POST['data']);

    $query = mysqli_query($conexao, "SELECT * FROM cliente WHERE idempresa='$idempresa' AND situacao='Ativo' AND contato <> ''");
    if (mysqli_num_rows($query) >= 1) {
        $n = 1;
        while ($ret = mysqli_fetch_array($query)) {
            $nome = $ret['nome'];
            $contato = $ret['contato'];

            //idempresa	notificacao	datadisparo	situacao
            mysqli_query($conexao, "INSERT INTO notificacao_agendada (idempresa,nome,contato,notificacao,datadisparo) 
        VALUES ('$idempresa','$nome','$contato','$msg','$data')") or die(mysqli_error($conexao));
        }
        echo persona2('Agendada com sucesso', 'success', 'check-square-o');
    }
}
