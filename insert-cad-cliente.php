<?php
include('conexao.php');
include('funcoes.php');

$idempresa2 = $_POST['idempresa'];
$plano = AspasBanco($_POST['plano']);
$vencimento = intval($_POST['vencimento']);
$tipo = $_POST['tipo'];
$nome = AspasBanco($_POST['nome']);
$cpf = limpaCPF_CNPJ($_POST['cpf']);
$rg = AspasBanco($_POST['rg']);
$emissor = AspasBanco($_POST['emissor']);
$rguf = AspasBanco($_POST['rguf']);
$nascimento = $_POST['nascimento'];
$cnpj = limpaCPF_CNPJ($_POST['cnpj']);
if ($_POST['nascimento'] != '') {
        $nascimento = dataBanco($_POST['nascimento']);
} else {
        $nascimento = '0000-00-00';
}
$contato = limpaCPF_CNPJ($_POST['contato']);
$email = $_POST['email'];
$cep = $_POST['cep'];
$endereco = AspasBanco($_POST['endereco']);
$numero = AspasBanco($_POST['numero']);
$bairro = AspasBanco($_POST['bairro']);
$municipio = AspasBanco($_POST['municipio']);
$estado = $_POST['estado'];
@$ibge = @$_POST['ibge'];
$latitude = AspasBanco($_POST['latitude']);
$longitude = AspasBanco($_POST['longitude']);

mysqli_query($conexao, "INSERT INTO cliente 
        (idempresa,tipo,nome,cpf,rg,rguf,emissor,nascimento,cnpj,contato,
        email,cep,rua,numero,bairro,municipio,estado,data,latitude,longitude,vencimento,obs)
VALUES ('$idempresa2','$tipo','$nome','$cpf','$rg','$rguf','$emissor','$nascimento',
        '$cnpj','$contato','$email','$cep','$endereco','$numero','$bairro','$municipio',
        '$estado',NOW(),'$latitude','$longitude','$vencimento','$plano')
        ") or die(mysqli_error($conexao));

echo "<script>window.location='obrigado.php?id=$idempresa2'</script>";
