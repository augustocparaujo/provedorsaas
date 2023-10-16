<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a�o
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
//sem limite de tempo
set_time_limit(0);
$hoje = date('Y-m-d');
//apenas ativos
$query0 = mysqli_query($conexao,"SELECT * FROM cliente
WHERE cliente.situacao='Ativo' AND tipodecobranca='PORTA PORTA' AND vencimento <> 0 AND vencimento <> ''") or die (mysqli_error($conexao));
$rows = mysqli_num_rows($query0);
@$i = 0;
for ($n=0; $n < $rows; $n++) { 
    $dd = mysqli_fetch_array($query0); 
    if($dd['vencimento'] <= 9) { $vencimento = '0'.$dd['vencimento']; }else{ $vencimento = $dd['vencimento']; }
    $idempresa = $dd['idempresa'];
    $idcliente = $dd['id'];
    $nomecliente = $dd['nome'];
  
       $buscac = mysqli_query($conexao,"SELECT contratos.plano, plano.plano AS planonome, plano.valor FROM contratos
      INNER JOIN plano ON contratos.plano = plano.id
      WHERE contratos.idcliente='$idcliente'") or die (mysqli_error($conexao));
      while($retC = mysqli_fetch_array($buscac)){
        $totalplano = $totalplano + $retC['valor'];
      }
  
    $valor = $totalplano;
    $vencimentomes = date('Y-m-'.$vencimento);
    //verifica se tem cobrança
        $query1 = mysqli_query($conexao,"SELECT * FROM cobranca 
        WHERE idcliente='$idcliente' AND tipocobranca='plano' AND vencimento='$vencimentomes' AND tipo='Manual'") or die (mysqli_error($conexao));
        $rowsc = mysqli_num_rows($query1);
        $ddc = mysqli_fetch_assoc($query1);            
        //tem ou não
        if($rowsc == 0){ 
            if($dd['tipodecobranca'] == 'PORTA PORTA'){
                $vencimento = $vencimentomes;
                gerarCobrancaSemBoleto($idempresa,$idcliente,$nomecliente,$vencimento,$valor);                
            }
        }
        @$i++;
}
print '<h3 style="color:red">Clientes a verificar: '.$rows.'<br/>
Verifocados: '.@$i.' 
<br/>Cobranças porta a porta serão geradas todo dia 1 do mês vigente, no fechar</h3>';
?>