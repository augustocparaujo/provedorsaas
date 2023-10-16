<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina


 $query = mysqli_query($conexao,"SELECT cliente.* FROM cliente WHERE idempresa='$idempresa' AND situacao='' ORDER BY id DESC") or die (mysqli_error($conexao));
     if(mysqli_num_rows($query) >= 1){
        while($dd = mysqli_fetch_array($query)){echo'
          <tr>
              <td>'.$dd['id'].'</td>
              <td style="color: blue; width:30%"><a href="clientes-exibir.php?id='.$dd['id'].'">'.substr($dd['nome'], 0, -1).'</a></td>
              <td>'; if($dd['cnpj'] != ''){ echo $dd['cnpj']; }else{ echo $dd['cpf'];} echo'</td>
              <td>Pré-cadastro ou em branco</td>
              <td>'.dataForm($dd['data']).'</td>
              <td><i class="fa fa-trash text-red fa-2x" style="cursor:pointer" onclick="excluir('.$dd['id'].')"></i></td>
          </tr>';
        }
      }
                    
?>