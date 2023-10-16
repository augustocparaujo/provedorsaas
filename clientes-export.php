<?php 
include('conexao.php');
$query = mysqli_query($conexao,"SELECT * FROM cliente WHERE idempresa='9999999999'") or die (mysqli_error($conexao));
while($dd = mysqli_fetch_array($query)){
echo 'add name="'.$dd['login'].'" password="'.$dd['senha'].'" service="pppoe" profile="'.$dd['nomeplano'].'"<br>';



}
?>