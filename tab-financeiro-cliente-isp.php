<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if (isset($_SESSION['iduser']) != true || empty($_SESSION['iduser'])) {
    echo '<script>location.href="sair.php";</script>';
}
//user_cobranca
//idcliente	link codigobarra	codigodelinhadigitavel	ncobranca	custom_id	cliente	vencimento	valor	datapagamento	situacao	pdf	qrcode	

@$id = $_GET['id'];
$query = mysqli_query($conexao, "SELECT * FROM user_cobranca WHERE idcliente='$id' ORDER BY vencimento DESC") or die(mysqli_error($conexao));

if (mysqli_num_rows($query) >= 1) {
    while ($dd = mysqli_fetch_array($query)) {
        echo '
        <tr>
            <td>'.$dd['id'].'/'.$dd['code'].'</td>
            <td>R$ ' . Real($dd['valor']) . '</td>
            <td>' . dataForm($dd['vencimento']) . '</td>
            <td>' . dataForm($dd['datapagamento']) . '</td>
            <td>'.diasVencidos($dd['vencimento']).'</td>
            <td>' . situacao($dd['situacao']) . '</td>
            <td>'; 
            if($dd['code'] != '' AND $dd['situacao'] == 'PENDENTE' OR $dd['situacao'] == 'VENCIDO'){echo'
                <a href="'.$dd['link'].'" target="_blank" title="Imprimir boleto"><i class="fa fa-barcode text-black fa-2x"></i></a>&ensp; 
                <a href="#" onclick="consultarCobrancaIsp('.$dd['code'].')" title="Consultar cobrança"><i class="fa fa-refresh fa-spin fa-2x"></i></a>&ensp;
                <a href="#" onclick="cancelarCobrancaIsp('.$dd['code'].')" title="cancelar cobrança"><i class="fa fa-close text-red fa-2x"></i></a>&ensp;';
            }elseif($dd['code'] == '' AND $dd['situacao'] == 'PENDENTE' OR $dd['situacao'] == 'VENCIDO'){echo'
                <a href="#" onclick="cancelarCobrancaIsp2('.$dd['id'].')" title="cancelar cobrança"><i class="fa fa-close text-red fa-2x"></i></a>&ensp;
                <a href="#" onclick="receberCobrancaIsp('.$dd['id'].')" title="Receber"><i class="fa fa-usd text-green fa-2x"></i></a>    
            ';}
            echo'</td>
        </tr>';
    }
} else {
    echo '<tr><td colspan="6">Sem registro</td></tr>';
}
