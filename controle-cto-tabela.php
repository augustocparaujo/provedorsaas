<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if (isset($_SESSION['iduser']) != true || empty($_SESSION['iduser'])) {
    echo '<script>location.href="sair.php";</script>';
}
@$cto = $_POST['cto'];
@$cliente = $_POST['cliente'];
$query = mysqli_query($conexao, "SELECT * FROM controle_cto WHERE idempresa='$idempresa' AND cto LIKE '%$cto%' AND cliente LIKE '%$cliente%'") or die(mysqli_error($conexao));
if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_array($query)) {
        echo '
        <tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['cto'] . '/' . $row['porta'] . '</td>
            <td>' . $row['empresa'] . '</td>
            <td>' . $row['cliente'] . '</td>
            <td>' . $row['localizacao'] . '/' . $row['estado'] . '</td>
            <td>' . $row['longitude'] . '/' . $row['latitude'] . '</td>
            <td>
                <a href="#" class="fa fa-pencil text-primary fa-2x" onclick="editar(' . $row['id'] . ')"></a>&nbsp;
                <a href="#" class="fa fa-trash text-red fa-2x" onclick="excluir(' . $row['id'] . ')" ></a>
            </td>
        </tr>
        ';
    }
} else {
    echo '<tr><td class="colspan="7">Sem registro</td></tr>';
}
