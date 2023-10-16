<?php
session_start();
include('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$sql = mysqli_query($conexao, "SELECT cliente.id,nome,cpf,cnpj,contato,vencimento, contratos.login FROM cliente 
LEFT JOIN contratos ON cliente.id = contratos.idcliente WHERE cliente.idempresa='$idempresa'");
echo '
<div class="card-body table-responsive p-0">
<table id="example" style="width:100%">
  <thead><tr>
    <th>#</th>
    <th>Nome</th>
    <th>CPF/CNPJ</th>
    <th>VENC</th>
    <th>Login</th>
    <th>#</th>
  </tr></thead><tbody>';
while ($ret = mysqli_fetch_array($sql)) {
    echo '
                <tr>
                  <td>' . $ret['id'] . '</td>
                  <td><a href="clientes-exibir.php?id=' . $ret['id'] . '" target="_blank">' . $ret['nome'] . '</a></td>
                  <td>' . @$ret['cpf'] . '' . @$ret['cnpj'] . '</td>
                  <td>' . $ret['vencimento'] . '</td>
                  <td>' . $ret['login'] . '</td>
                  <td>
                  ';
    if (PermissaoCheck($idempresa, 'clientes-financeiro', $_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin') {
        echo '
                                  <a href="clientes-financeiro-exibir.php?id=' . $ret['id'] . '" title="receber"><i class=" fa fa-dollar fa-2x text-green"></i></a>&ensp;';
    }
    if (PermissaoCheck($idempresa, 'clientes-chamado', $_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin') {
        echo '
                                  <a onclick="abrirChamado(' . $ret['id'] . ')"><i class="fa fa-headphones fa-2x"></i></a>&ensp;';
    }
    if (PermissaoCheck($idempresa, 'clientes-whatsapp', $_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin') {
        echo '
                                  <a onclick="whatsapp(' . $ret['contato'] . ')"><i class="fa fa-whatsapp fa-2x text-green"></i></a>&ensp;';
    }

    if (PermissaoCheck($idempresa, 'clientes-excluir', $_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin') {
        echo '
   <a onclick="excluirCliente(' . $ret['id'] . ')" class="fa fa-trash text-red fa-2x"></a>';
    }
    echo '
                  
                  
                  </td>
              </tr>';
}
echo '
</tbody></table>
</div>';
?>
<script type="text/javascript" src="plugins/dataTable/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="plugins/dataTable/jquery.dataTables.min.css" />
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "language": {
                "lengthMenu": "Exibir _MENU_ linhas",
                "zeroRecords": "Sem registro",
                "info": "Linhas de _PAGE_ at&eacute; _PAGES_",
                "infoEmpty": "Nenhum registro dispon&iacute;vel",
                "infoFiltered": "(filtrados de _MAX_ total de linhas)"
            },
            stateSave: true,
            "order": [
                [0, "asc"]
            ],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ]
        });
    });
</script>