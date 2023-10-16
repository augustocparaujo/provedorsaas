<?php
echo'<b style="color:red">Pode continuar trabalhando em outra janela, não fehce essa!</b><br />Verificação em andamento entre datas:'.date('01-m-Y').' e '.date('t-m-Y').' -> Processando.... <br />';
set_time_limit(0);
session_start();
include('conexao.php'); 
include('api_bb.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
$iniciomes = date('Y-m-01');
$fimmes = date('Y-m-t');
//criar função de verificação se estar vencida ou não e alterar a situacção
$query = mysqli_query($conexao,"SELECT * FROM cobranca
WHERE idempresa='$idempresa' AND vencimento BETWEEN '$iniciomes' AND '$fimmes' AND situacao IN ('PENDENTE','VENCIDO') AND banco='Banco do Brasil'") or die (mysqli_error($conexao));
$rows = mysqli_num_rows($query);
echo'Total a serem verificados:'. $rows.'<br />';
sleep(5);
$n = 1;
if($rows >= 1){
    while($ret = mysqli_fetch_array($query)){
        echo ' '.$n.' - Empresa: '.$ret['idempresa'].' - Titulo: '.$ret['idcobranca'].' - Cliente: '.$ret['cliente'].' | Vencimento: '.date('d-m-Y',strtotime($ret['vencimento'])).' <i style="color:red">verificando...</i><br>';
        $id = $ret['id'];  
        //pegar empresa chamar consulta pela empresa e token
        consultarCobranca($id);
        $n++;
        sleep(3);
    }
}
echo'Total de cobranças verificadas: '.$rows;

?>
