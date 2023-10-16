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

@$id = $_GET['id'];
$query = mysqli_query($conexao, "SELECT cobranca.*, cliente.contato FROM cobranca 
LEFT JOIN cliente ON cobranca.idcliente = cliente.id
WHERE cobranca.idcliente='$id' AND cobranca.idempresa='$idempresa' AND cobranca.situacao IN ('PENDENTE','VENCIDO')
ORDER BY cobranca.vencimento ASC") or die(mysqli_error($conexao));

if (mysqli_num_rows($query) >= 1) {
    while ($dd = mysqli_fetch_array($query)) {
        echo '
<tr>
    <td>';
        if ($dd['code'] != '') {
            echo $dd['code'];
        } else {
            echo $dd['ncobranca'];
        }
        echo '</td>
    <td>' . $dd['banco'] . '</td>
    <td>' . date('d-m-Y', strtotime($dd['vencimento'])) . '</td>
    <td>R$ ' . Real($dd['valor']) . '</td>
    <td>';
        if ($dd['datapagamento'] != '0000-00-00') {
            echo date('d-m-Y', strtotime($dd['datapagamento']));
        }
        echo '</td>
    <td>' . situacao($dd['situacao']) . '</td>
    <td>';
        if ($dd['banco'] == 'Banco Iugo' or $dd['banco'] == 'Gerencianet') {

            if ($dd['situacao'] == 'RECEBIDO') {
                echo '
                    <a href="imprimir-recibo-cobranca.php?id=' . $dd['id'] . '"  donwload target="_blank" title="Recibo"><i class="fa fa-file-pdf-o text-red "></i></a>&ensp; 
                    <a href="#" onclick="exibirRecebido(' . $dd['ncobranca'] . ')" title="eixibir"><i class="fa fa-file-text-o  text-blue"></i></a>&ensp; ';
            } else {
                if ($dd['nparcela'] >= 2) {
                    echo '<a href="' . $dd['installmentLink'] . '" target="_blank" title="Imprimir boleto"><i class="fa fa-credit-card text-black "></i></a>&ensp; ';
                }
                echo '
                    <a href="' . $dd['link'] . '" target="_blank" title="Imprimir boleto"><i class="fa fa-barcode text-black "></i></a>&ensp;
                    <a href="#" onclick="receberCobranca(' . $dd['id'] . ')" title="Receber"><i class="fa fa-usd text-green "></i></a>&ensp;';

                if ($dd['banco'] == 'Gerencianet') {
                    echo '<a href="#" onclick="pix(' . $dd['id'] . ')" title="Cópiar código pix"><i class="fa fa-chain  text-black"></i></a>&ensp; ';
                }
                echo '
                    <a onclick="enviaBoleto(' . $dd['id'] . ')" title="Enviar boleto"><i class="fa fa-whatsapp text-green "></i></a>&ensp;
                    <a href="#" onclick="cancelarCobranca(' . $dd['id'] . ')" title="cancelar cobrança"><i class="fa fa-close text-red "></i></a>&ensp; 
                    <a href="#" onclick="consultarCobranca(' . $dd['id'] . ')" title="Consultar cobrança"><i class="fa fa-refresh fa-spin "></i></a>&ensp; ';
            }
        } elseif ($dd['banco'] == 'Carteira') {
            if ($dd['situacao'] == 'RECEBIDO') {
                echo '
                    <a href="imprimir-recibo-cobranca.php?id=' . $dd['id'] . '" donwload target="_blank" title="Recibo"><i class="fa fa-file-pdf-o text-red "></i></a>&ensp; 
                    <a href="#" onclick="exibirRecebido(' . $dd['ncobranca'] . ')" title="eixibir"><i class="fa fa-file-text-o  text-blue"></i></a>&emsp;';
            }
            if ($dd['banco'] == 'Carteira' and $dd['situacao'] == 'PENDENTE' or $dd['situacao'] == 'VENCIDO') {
                echo '<a href="carne/carne.php?id=' . $dd['id'] . '" target="_blank" title="Imprimir"><i class="fa fa-barcode text-black "></i></a>&ensp; ';
                if ($dd['nparcela'] >= 2) {
                    echo '
                        <a href="carne/carnes.php?id=' . $dd['idcobrancaprincipal'] . '" target="_blank" title="Imprimir"><i class="fa fa-copy text-black "></i></a>&ensp; 
                        ';
                }
                echo '
                <a onclick="enviaBoleto(' . $dd['id'] . ')" title="Enviar boleto"><i class="fa fa-whatsapp text-green "></i></a>&ensp;

                    <a href="#" onclick="receberCobranca(' . $dd['id'] . ')" title="Receber"><i class="fa fa-usd text-green "></i></a>&ensp; 
                    <a href="#" onclick="cancelarCobranca(' . $dd['id'] . ')" title="Cancelar"><i class="fa fa-close text-red "></i></a>&ensp; ';
            }
        }

        echo '
    </td>
</tr>';
    }
} else {
    echo '<div class="col-lg-12 text-red">Sem cobranças</div>';
}
