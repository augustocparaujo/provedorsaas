<?php

include('../conexao.php');
include('../funcoes.php');
session_start();

@$acesso = limpaCPF_CNPJ($_POST['usuario']);
@$senha = substr(limpaCPF_CNPJ($_POST['senha']), 0, 6);
if (strlen($acesso) == 11) {
	$variavel = 'cpf=' . $acesso;
} elseif ($acesso == 14) {
	$variavel = 'cnpj=' . $acesso;
} else {
	$acesso = 0;
}; //cpf ou cnpj
//calculo de senha 6 numero do cpf ou cnpj
$cal = substr($acesso, 0, 6);
if ($cal == $senha) {
	$pass = 1;
} else {
	$pass = 0;
};

//divisão adm
if ($pass == 1) {
	if (isset($acesso) != '' and isset($senha) != '') {
		$sql = mysqli_query($conexao, "SELECT * FROM cliente WHERE $variavel LIMIT 1") or die(mysqli_error($conexao));
		$ddu = mysqli_fetch_array($sql);
		if (empty($ddu)) {
			echo '<button class="alert alert-danger btn-block"><i class="fa fa-exclamation-triangle"></i> Usuário ou senha inválido!</button>';
		}  //se $dados_uu for vazio mostrar o erro
		else {

			$_SESSION['idempresa'] = $ddu['idempresa'];
			$_SESSION['iduser'] = $ddu['id'];
			$_SESSION['usuario'] = $ddu['nome'];

			$idempresa = $_SESSION['idempresa'];
			$iduser = $_SESSION['iduser'];
			$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
			$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
			$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

			echo "<script>location.href='index.php';</script>";
		}
	} else {
		echo '<button class="alert alert-danger btn-block"><i class="fa fa-exclamation-triangle"></i> Usuário ou senha inválido!</button>';
	}
	//user staff
} else {
	echo '<button class="alert alert-danger btn-block"><i class="fa fa-exclamation-triangle"></i> Usuário ou senha inválido!!</button>';
}
