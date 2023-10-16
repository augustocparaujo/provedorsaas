

<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$iduser = $_SESSION['iduser'];
@$iduser = $_SESSION['iduser'];
@$tipouser = $_SESSION['tipouser'];
if (isset($_SESSION['iduser']) != true and isset($_SESSION['situacaouser']) != true) {
  echo '<script>location.href="sair.php";</script>';
}
@$id = $_GET['id'];
$query = mysqli_query($conexao, "SELECT * FROM contratos WHERE idcliente='$id'") or die(mysqli_error($conexao));
while ($dd = mysqli_fetch_array($query)) {
  echo '
<tr>
    <td>' . $dd['id'] . '</td>
    <td>' . AspasForm($dd['nomeplano']) . '</td>
    <td>' . AspasForm($dd['login']) . '</td>   
    <td>
      <a href="#" onclick="testarConexao(' . $dd['id'] . ')" class="btn btn-primary ajax"><i class="fa fa-gamepad"></i> Verificar</a>
    </td>
    <td>
        <a href="clientes-contrato-exibir.php?id=' . $dd['id'] . '"><i class="fa fa-edit fa-2x"></i></a>&ensp;
        <a href="" title="Gerar contrato"><i class="fa fa-file-pdf-o fa-2x text-red"></i></a>&ensp;
        <a href="#" onclick="excluirContrato(' . $dd['id'] . ')"><i class="fa fa-trash fa-2x text-red"></i></a>
    </td>
</tr>';
}
