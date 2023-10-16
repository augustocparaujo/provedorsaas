<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }

$sql = mysqli_query($conexao, "SELECT j_estoque.*, j_categoria_estoque.nome_cat AS cat, j_fornecedor_equip.descricao AS forec FROM j_estoque
LEFT JOIN j_categoria_estoque ON j_estoque.categoria = j_categoria_estoque.id
LEFT JOIN j_fornecedor_equip ON j_estoque.fornecedor = j_fornecedor_equip.id
WHERE j_estoque.idempresa='$idempresa' AND j_estoque.situacao='ativo' ORDER BY j_estoque.descricao ASC") or die(mysqli_error($conexao));

if(mysqli_num_rows($sql) >= 1){
while ($dd = mysqli_fetch_array($sql)) {
        echo'<tr>
            <td>'.$dd['id'].'</td>
            <td>'.$dd['cat'].'</td>
            <td>'.$dd['forec'].'</td>
            <td>'.AspasForm($dd['descricao']).'</td>
            <td>'.$dd['quantidade'].'</td>
            <td>'.$dd['usuariocad'].'</td>
            <td>'.dataForm($dd['data']).'</td>
            <td>';
            if(PermissaoCheck($idempresa,'exibir-item',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                <a onclick="exibirItem('.$dd['id'].')" class="btn btn-social-icon btn-black" title="exibir movimento"><i class="fa fa-exchange"></i></a>&ensp;';
            }
            if(PermissaoCheck($idempresa,'alterar-item',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                <a onclick="alterarItem('.$dd['id'].')" class="btn btn-social-icon btn-edit" title="alterar item"><i class="fa fa-edit"></i></a>&ensp;';
            }
            if(PermissaoCheck($idempresa,'saida-item',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                <a onclick="saidaItem('.$dd['id'].')" class="btn btn-social-icon btn-edit" title="saída item"><i class="fa fa-minus-square"></i></a>&ensp;';
            }
            if(PermissaoCheck($idempresa,'excluir-item',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                <a onclick="excluirItem('.$dd['id'].')" class="btn btn-social-icon btn-trash" title="excluir item"><i class="fa fa-trash"></i></a>';
            }echo'
            </td>
        </tr>';

}}else{ echo'<tr><td colspan="7">sem registro</td></tr>';}
?>