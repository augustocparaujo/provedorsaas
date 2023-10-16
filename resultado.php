<?php
session_start();
require('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];

$query = mysqli_query($conexao, "SELECT * 
FROM cliente
WHERE cliente.idempresa='$idempresa' AND cliente.latitude <> '' AND cliente.longitude <> ''") or die(mysqli_error($conexao));


header("Content-type: text/xml");
// Start XML file, echo parent node
echo '<markers>';
// Iterate through the rows, printing XML nodes for each
while ($ret = mysqli_fetch_assoc($query)) {

  // Add to XML document node
  echo '<marker ';
  //echo 'name="' . parseToXML($ret['name']) . '" ';
  // echo 'address="' . parseToXML($ret['rua']) . '" ';
  echo 'name="Nome: ' . parseToXML($ret['nome']) . '" ';
  echo 'address="Endereço: ' . parseToXML($ret['rua']) . '" ';
  echo 'lat="' . $ret['latitude'] . '" ';
  echo 'lng="' . $ret['longitude'] . '" ';
  //echo 'type="' . $ret['type'] . '" ';
  echo 'type="padrao" ';
  echo '/>';
}
// End XML file
echo '</markers>';
