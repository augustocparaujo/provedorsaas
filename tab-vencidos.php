<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = @$_SESSION['idempresa'];
    $query = mysqli_query($conexao,"SELECT cobranca.*, cliente.nome,cliente.situacao AS SitCliente,cpf,cnpj FROM cobranca
    LEFT JOIN cliente ON cobranca.idcliente = cliente.id
    WHERE cobranca.idempresa='$idempresa' AND cobranca.situacao='VENCIDO' ORDER BY cliente.nome ASC") or die (mysqli_error($conexao));
if(mysqli_num_rows($query) >= 1){
    while ($dd = mysqli_fetch_array($query)) {   
        if($teste != $dd['idcliente']){
        echo'<tr>
            <td>'.$dd['idcliente'].'</td>
            <td>';
                if($dd['SitCliente'] == 'Ativo'){ echo'<span class="label label-success">'.$dd['SitCliente'].'</span>'; }
                else{ echo'<span class="label label-danger">'.$dd['SitCliente'].'</span>'; }
            echo'
            </td>
            <td>'.$dd['nome'].'</td>
            <td>'.@$dd['cpf'].' '.@$dd['cnpj'].'</td>
            <td>'; 
            $query0 = mysqli_query($conexao,"SELECT cobranca.vencimento FROM cobranca WHERE idcliente='$dd[idcliente]' AND situacao='VENCIDO'");
            while($ret = mysqli_fetch_array($query0)){
                echo dataForm($ret['vencimento']).'<br>'; 
            }
            echo'</td>            
            <td>
                <a class="btn btn-danger" title="Bloquear" onclick="bloquearCliente('.$dd['idcliente'].')"><i class="fa fa-user-times"></i></a>&ensp;
                <a onclick="exibir('.$dd['idcliente'].')"" class="btn btn-social-icon btn-edit"><i class="fa fa-pencil"></i></a>
            </td>
        </tr>';
        $teste = $dd['idcliente'];
        }
    }
}

?>



