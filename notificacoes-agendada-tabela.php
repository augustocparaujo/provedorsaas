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

$inicio = date('Y-m-01');
$fim = date('Y-m-t');
$query = mysqli_query($conexao, "SELECT * FROM notificacao_agendada WHERE idempresa='$idempresa' and datadisparo BETWEEN '$inicio' AND '$fim' ORDER BY nome,datadisparo ASC") or die(mysqli_error($conexao));
if (mysqli_num_rows($query) > 0) {
    while ($dd = mysqli_fetch_array($query)) {
        echo '
        <tr title="' . $dd['notificacao'] . '">
            <td>' . $dd['id'] . '</td>
            <td>' . $dd['idcobranca'] . '</td>
            <td>' . $dd['nome'] . '</td>
            <td>' . $dd['contato'] . '</td>
            <td>' . dataForm($dd['datadisparo']) . '</td>
            <td>';
        if ($dd['situacao'] == 1) {
            echo 'Enviado';
        } else {
            echo 'Ainda não enviado';
        }
        echo '</td>
            <td><a href="#" onclick="excluir(' . $dd['id'] . ')" class="fa fa-trash fa-2x text-red"></a></td>
        </tr>';
    }
} else {
    echo '<tr><td colspan="7">Sem registro</td></tr>';
}
