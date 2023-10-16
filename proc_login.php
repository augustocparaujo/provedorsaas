<?php

include('conexao.php');
session_start();
@$user = md5($_POST['usuario']);
@$senha = md5($_POST['senha']);
@$tipo = $_POST['tipo'];
//divisão adm
if ($tipo == 1) {
	if (isset($user) != '' and isset($senha) != '') {
		$sql = mysqli_query($conexao, "SELECT * FROM user WHERE user='$user' AND senha='$senha' AND situacao=1 LIMIT 1") or die(mysqli_error($conexao));
		$ddu = mysqli_fetch_array($sql);
		if (empty($ddu)) {
			echo '<button class="alert alert-danger btn-block"><i class="fa fa-exclamation-triangle"></i> Usuário ou senha inválido!</button>';
		}  //se $dados_uu for vazio mostrar o erro
		else {
			if ($ddu['tipo'] != '') {
				$_SESSION['tipouser'] = $ddu['tipo'];
			} else {
				$_SESSION['tipouser'] = 'comum';
			}
			$_SESSION['idempresa'] = $ddu['idempresa'];
			$_SESSION['tipouser'] = $ddu['tipo'];
			$_SESSION['cargo'] = $ddu['cargo'];
			$_SESSION['iduser'] = $ddu['id'];
			$_SESSION['usuario'] = $ddu['nome'];
			$_SESSION['situacaouser'] = $ddu['situacao'];
			$_SESSION['logomarcauser'] = $ddu['logomarca'];
			$_SESSION['hash'] = md5(time());
			$_SESSION['sistema'] = $ddu['sistema'];

			$idempresa = $_SESSION['idempresa'];
			$tipouser = $_SESSION['tipouser'];
			$usercargo = $_SESSION['cargo'];
			$iduser = $_SESSION['iduser'];
			$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
			$situacaouser = $_SESSION['situacaouser'];
			$logomarcauser = $_SESSION['logomarcauser'];
			$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
			$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

			echo "<script>location.href='index.php';</script>";
		}
	} else {
		echo '<button class="alert alert-danger btn-block"><i class="fa fa-exclamation-triangle"></i> Usuário ou senha inválido!</button>';
	}
	//user staff
} elseif ($tipo == 2) {
	if (isset($user) != '' and isset($senha) != '') {
		$sql = mysqli_query($conexao, "SELECT * FROM usuarios WHERE login='$user' AND senha='$senha' AND situacao=1 LIMIT 1") or die(mysqli_error($conexao));
		$ddu = mysqli_fetch_array($sql);
		if (empty($ddu)) {
			echo '<button class="alert alert-danger btn-block"><i class="fa fa-exclamation-triangle"></i> Usuário ou senha inválido!</button>';
		}  //se $dados_uu for vazio mostrar o erro
		else {
			$_SESSION['idempresa'] = $ddu['idempresa'];
			$_SESSION['tipouser'] = 'Staff';
			$_SESSION['cargo'] = 'Staff';
			$_SESSION['iduser'] = $ddu['id'];
			$_SESSION['usuario'] = $ddu['nome'];
			$_SESSION['situacaouser'] = $ddu['situacao'];
			$_SESSION['logomarcauser'] = $ddu['logomarca'];

			$idempresa = $_SESSION['idempresa'];
			$tipouser = $_SESSION['tipouser'];
			$usercargo = $_SESSION['cargo'];
			$iduser = $_SESSION['iduser'];
			$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
			$situacaouser = $_SESSION['situacaouser'];
			$logomarcauser = $_SESSION['logomarcauser'];
			$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
			$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

			echo "<script>location.href='index.php';</script>";
		}
	} else {
		echo '<button class="alert alert-danger btn-block"><i class="fa fa-exclamation-triangle"></i> Usuário ou senha invlido!</button>';
	}
} else {
	echo 'erro maluco ;)';
}
