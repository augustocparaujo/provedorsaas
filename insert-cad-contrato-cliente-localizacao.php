<?php
include('conexao.php'); 
include('funcoes.php');
$idcontrato = $_POST['idcontrato'];
$idcliente = $_POST['idcliente'];
$idempresa = $_POST['idempresa'];
$latitude = AspasBanco($_POST['latitude']);
$longitude = AspasBanco($_POST['longitude']);

if($idcliente != '' AND $latitude != '' AND $longitude != ''){
mysqli_query($conexao,"UPDATE contratos SET latitude='$latitude',longitude='$longitude' WHERE id='$idcontrato'") or die (mysqli_error($conexao));
}
echo"<script>window.location='obrigado.php?id=$idempresa'</script>";

?>