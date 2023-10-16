<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = @$_SESSION['idempresa'];
$query = mysqli_query($conexao, "SELECT cliente.*, contratos.nomeplano AS nomeplano2,contratos.login AS login2 FROM cliente 
    LEFT JOIN contratos ON cliente.id = contratos.idcliente
    WHERE cliente.idempresa='$idempresa' AND cliente.situacao='Bloqueado'") or die(mysqli_error($conexao));


$n = 1;
if (mysqli_num_rows($query) >= 1) {
    while ($dd = mysqli_fetch_array($query)) {

        echo '<tr>
            <td>' . $n . '</td>
            <td>' . $dd['nome'] . '</td>
            <td>' . @$dd['cpf'] . ' ' . @$dd['cnpj'] . '</td>
            <td>';

        $login = $dd['login2'];
        @$plano = $dd['nomeplano2'];

        $queryplano = mysqli_query($conexao, "SELECT plano.*, servidor.id,ip,user,password FROM plano
            LEFT JOIN servidor ON plano.servidor = servidor.id
            WHERE plano.id='$plano'");
        $retorno = mysqli_fetch_array($queryplano);
        $ipservidor = @$retorno['ip'];
        $user = @$retorno['user'];
        $passwords = @$retorno['password'];

        require_once('routeros_api.class.php');
        $mk = new RouterosAPI();
        if ($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
            $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));
            //existe
            if (count($find) >= 1) {
                echo '<i class="fa fa-circle" style="color:green" title="online"></i> on';
            } else {
                echo '<i class="fa fa-circle" style="color:red"></i> off';
            }
        }




        echo '</td>
            <td>' . @$dd['nomeplano'] . '</td>
            <td>' . $dd['usuarioatualizou'] . '</td>
            <td>' . dataForm($dd['atualizado']) . '</td>
            <td><span class="label label-danger">' . $dd['situacao'] . '</span></td>
            <td><a onclick="exibir(' . $dd['id'] . ')""><i class="fa fa-pencil fa-2x text-blue"></i></a></td>
        </tr>';
        $n++;
    }
}
