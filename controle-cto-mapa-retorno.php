<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if (isset($_SESSION['iduser']) != true) {
    echo '<script>location.href="sair.php";</script>';
}

// Select all the rows in the markers table
$query = mysqli_query($conexao, "SELECT * FROM controle_cto WHERE idempresa='$idempresa'");
header("Content-type: text/xml");
// Start XML file, echo parent node
echo '<markers>';
// Iterate through the rows, printing XML nodes for each
while ($rows = mysqli_fetch_array($query)) {
    // Add to XML document node
    echo '<marker ';
    echo 'name="' . parseToXML('CTO: ' . $rows['cto']) . '" ';
    echo 'address="' . parseToXML($rows['cliente']) . '" ';
    echo 'lat="' . $rows['latitude'] . '" ';
    echo 'lng="' . $rows['longitude'] . '" ';
    echo 'type="' . $rows['cto'] . '" ';
    echo '/>';
}
// End XML file
echo '</markers>';
