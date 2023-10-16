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

if(isset($_POST['queryString'])) {
	$queryString = $_POST['queryString'];
	if(strlen($queryString) >0) {
        $query = mysqli_query($conexao,"SELECT cliente.*, plano.plano AS nomeplano FROM cliente 
        INNER JOIN plano ON cliente.plano = plano.id
        WHERE cliente.idempresa='$idempresa' AND plano.idempresa='$idempresa' AND nome LIKE '%$queryString%' OR cpf LIKE '%$queryString%' OR cnpj LIKE '%$queryString%'") or die (mysqli_error($conexao));

        while($dd = mysqli_fetch_array($query)){echo'
        <tr>
            <td><input type="checkbox" name="id[]" value="'.$dd['id'].'"></td>
            <td style="cursor: pointer; color: blue" onclick="exibir('.$dd['id'].')">'.$dd['nome'].'</td>
            <td>'; if($dd['cnpj'] != ''){ echo $dd['cnpj']; }else{ echo $dd['cpf'];} echo'</td>
            <td>';
            if($dd['nomeplano'] == ''){ echo'<span class="label label-warning">sem plano</span>';}else{ echo $dd['nomeplano'];} echo'</td>
            <td>';
            if($dd['situacao'] == 'Ativo'){ echo'<span class="label label-success">'.$dd['situacao'].'</span>';}
            elseif($dd['situacao'] == 'Bloqueado' || $dd['situacao'] == 'Cancelado'){echo'<span class="label label-danger">'.$dd['situacao'].'</span>';} 
            elseif($dd['situacao'] == 'Pêndencia'){echo'<span class="label label-warning">'.$dd['situacao'].'</span>';} 
            elseif($dd['situacao'] == ''){echo'<span class="label label-info">sem nada</span>';}                                   
            echo'</td>
            <td  style="padding:2px !important">';
            if(PermissaoCheck($idempresa,'clientes-financeiro',$_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin'){echo'
                <a href="exibir-financeiro-cliente.php?id='.$dd['id'].'" class="btn btn-social-icon btn-dollar" title="receber" target="_blank"><i class=" fa fa-dollar"></i></a>&ensp;';
            }
            if(PermissaoCheck($idempresa,'clientes-chamado',$_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin'){echo'
                <a onclick="abrirChamado('.$dd['id'].')" class="btn btn-social-icon btn-edit"><i class="fa fa-headphones"></i></a>&ensp;';
            }
            if(PermissaoCheck($idempresa,'clientes-whatsapp',$_SESSION['iduser']) == 'checked' || $_SESSION['tipouser'] == 'Admin'){echo'
                <a onclick="whatsapp('.$dd['contato'].')" class="btn btn-social-icon btn-whatsapp"><i class="fa fa-whatsapp"></i></a>';
            }echo'
            </td>
        </tr>';
        }
    }
}else{ 
    echo'<tr><td>Digite algo</td></tr>';
}
?>