<style>
  .live {
    height: 15px;
    width: 15px;
    border-radius: 50%;
    background-color: #00EE00;
    animation: pulse 1500ms infinite;
  }
    @keyframes pulse {
      0% {
        box-shadow: #00EE00 0 0 0 0;
      }
      75% {
        box-shadow: #ff69b400 0 0 0 16px;
      }
    }
</style>
<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$sql = mysqli_query($conexao,"SELECT * FROM servidor WHERE idempresa='$idempresa' LIMIT 1");
$reto = mysqli_fetch_array($sql);
echo '
<div class="card-body table-responsive p-0">
<table id="example" style="width:100%">
  <thead><tr>
    <th>#</th>
    <th>Nome</th>
    <th>CPF/CNPJ</th>
    <th>Login</th>
    <th>IP</th>
    <th>Roteador</th>
    <th><i class="fa fa-clock-o"></i> Tempo</th>
    <th>#</th>
  </tr></thead><tbody>';                
  //se houver session mikrotik ativa
      require_once('routeros_api.class.php');
      $mk = new RouterosAPI();
      if($mk->connect($reto['ip'], decrypt($reto['user']), decrypt($reto['password']))){
        $n = 1;       
        $find = $mk->comm("/ppp/active/print");
          if (count($find) >= 1) {
            foreach ($find as $key => $value) {  
              $login = $find[$key]['name'];
              $sql0 = mysqli_query($conexao,"SELECT cliente.*, contratos.login,contratos.porta FROM cliente 
              LEFT JOIN contratos ON cliente.id = contratos.idcliente
              WHERE contratos.login='$login'");
              $retorno = mysqli_fetch_array($sql0);

              echo '
                <tr>
                  <td>'.$n.'</td>
                  <td><a href="clientes-exibir.php?id='.$retorno['id'].'" target="_blank">';
                    
                  if($retorno['nome'] != ''){ echo $retorno['nome']; } else { echo'<i class="text-red">Sem cadastro</i>'; }
                    
                    echo'</a></td>
                  <td>'.@$retorno['cpf'].''.@$retorno['cnpj'].'</td>
                  <td>'.$find[$key]['name'].'</td>
                  <td>'.$find[$key]['address'].'</td>
                  <td>';
                  if($retorno['porta'] != ''){ echo'<a href="http://'.$find[$key]['address'].':'.$retorno['porta'].'" target="blank"><i class="fa fa-wrench"></i> accesar</a>';}
                  else{echo'<a href="http://'.$find[$key]['address'].'" target="blank"><i class="fa fa-wrench"></i> accesar</a>';}echo'
                  </td>
                  <td>'.$find[$key]['uptime'].'</td>
                  <td>
                    <i class="fa fa-bar-chart" onclick="consumoC(\''.$find[$key]['name'].'\','.$reto['id'].')" style="cursor: pointer" title="consumo"></i> &ensp;
                    <i class="fa fa-chain-broken text-blue" style="display:none" onclick="derrubar(\''.$find[$key]['.id'].'\')" style="cursor: pointer;" title="derrubar"></i> &ensp;
                    <i class="fa live" title="online"></i>                 
                  </td>
              </tr>';
              $n++;
            }
               
          } else {
              echo'não existe';
          }
      } else {
      echo persona("Falha na conexão com: (".$reto['ip'].")");
      }
                                  
  echo'
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
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
});
</script>