<style>
	@media print {
		.no-print {
			display: none;
		}
	}
    .ladoalado{
        display: inline-block;
    }
</style>

<?php
session_start();
include 'conexao.php'; 
include 'funcoes.php';
include_once 'por-extenso.php';
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$logomarcauser = $_SESSION['logomarcauser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

if(!empty(@$idempresa)){

    if(!empty(@$_GET['id'])){
        $id = $_GET['id'];
        //informações do emitente
        $query = mysqli_query($conexao,"SELECT * FROM user WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
        $dde = mysqli_fetch_array($query);

        //informações do recibo
        $sql = mysqli_query($conexao,"SELECT cobranca.*, caixa.nrecibo FROM cobranca
        LEFT JOIN caixa ON cobranca.ncobranca = caixa.titulo
        WHERE cobranca.idempresa='$idempresa' AND cobranca.id='$id'") or die (mysqli_error($conexao));
        $dd = mysqli_fetch_array($sql);
        $idcliente = $dd['idcliente'];
        if($dd['nrecibo'] != ''){ $tokenRecibo = $dd['nrecibo']; }else{
            $tokenRecibo = md5(date('Hms'));
        }

        //informações do cpf_cnpj
        $sql2 = mysqli_query($conexao,"SELECT * FROM cliente WHERE idempresa='$idempresa' AND id='$idcliente'") or die (mysqli_error($conexao));
        $dd2 = mysqli_fetch_array($sql2);

        //informações do contrato
        $sql3 = mysqli_query($conexao,"SELECT * FROM contratos WHERE idcliente='$idcliente'") or die (mysqli_error($conexao));
        if(mysqli_num_rows($sql3) >= 2){
            while($dd3 = mysqli_fetch_array($sql3)){
                @$contratos = @$contratos.'Contrato'.$dd['id'].' Plano: '.$dd3['nomeplano'].' / ';
            }
        }else{
            $dd3 = mysqli_fetch_array($sql3);
            @$contratos = 'Contrato Nº: '.$dd['id'].' - Plano: '.$dd3['nomeplano'];
        }
        
        if($dd2['cpf'] != ''){ $clienteR = $dd2['cpf']; }else{ $clienteR = $dd2['cnpj']; }
      for($i = 1; $i <= 2; $i++){

    echo '
    <div style="font-size:9px; font-family:Arial, sans-serif;">
    <hr class="traco">
    <div style="width: 100%" >
        <div class="ladoalado"">';
            if(!empty($_SESSION['logomarcauser'])){
                echo'<img src="logocli/'.$logomarcauser.'" width="100px">';
            }
        echo'
        </div>
        <div class="ladoalado">
            <h1>RECIBO</h1>
        </div>
    </div>

   
    <h2>&emsp; Valor: R$ '.Real($dd['valorpago']).'</h2>


    <p style="font-size: 14px">
    
        &emsp; <b>CÓDIGO ASSINANTE:</b> '.$dd2['id'].' - <b>CNPJ/CPF:</b> '.$clienteR.'<br/>
        &emsp; <b>ASSINANTE:</b> '.strtoupper($dd2['nome']).'<br/> 
        &emsp; <b>PLANO(s):</b> '.$contratos.'.
        <br/><br/>
    
        &emsp; Recebemos do(a) a import&acirc;ncia de R$'.Real($dd['valorpago']).' (<b>'.strtoupper(Extenso::converte(Real($dd['valorpago']), true, false)).'</b>), referente a recebimento da fatura: '.$dd['ncobranca'].'.<br/>
    <br/>
    &emsp; <b>ENDEREÇO:</b> '.$dd2['rua'].' '.$dd2['numero'].' <b>BAIRRO:</b> '.$dd2['bairro'].'</br>
    &emsp; <b>CIDADE:</b> '.$dd2['municipio'].'- <b>UF:</b>'.$dd2['estado'].' - <b>CEP:</b> '.$dd2['cep'].'</br>
    <br/>
    &emsp; <b>TOKEN DO RECIBO:</b> '.$tokenRecibo.'<br/>
    &emsp; <b>DATA DO VENCIMENTO:</b> '.dataForm($dd['vencimento']).'<br/>
    &emsp; <b>DATA DO PAGAMENTO:</b> '.dataForm($dd['datapagamento']).'
    
    </p>

    <hr class="traco">
        <div style="text-align:center;font-size:12px">
            '.strtoupper($dde['fantasia']).' - CPF/CNPJ: '.$dde['cpf_cnpj'].'<br>
            Contato: '.$dde['contato'].' E-mail: '.$dde['email'].'<br>
            Endereço: '.$dde['rua'].' - Bairro: '.$dde['bairro'].' Cidade: '.$dde['cidade'].'/'.$dde['estado'].'<br>
            Data/Hora&emsp;'.date('d-m-Y').'&emsp;'.date('H:i').'
        </div>
    </div><br><br>'; 
      }
      }

}else{
    echo persona('Algo deu errado :( !');
}
?>

<script> window.print(); </script>