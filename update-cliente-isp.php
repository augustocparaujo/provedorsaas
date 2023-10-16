<?php
session_start();
include('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a�o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

//tipo,logomarca,cargo,nome,fantasia,cpf_cnpj,rg,email,nascimento,contato,contato2,cep,rua,bairro,cidade,estado,user,senha,datacadastro,situacao,obs

@$id = $_POST['id'];
@$nome = $_POST['nome'];
@$fantasia = $_POST['fantasia'];
@$email = $_POST['email'];
@$cpf_cnpj = limpaCPF_CNPJ($_POST['cpf_cnpj']);

$isestadual = $_POST['isestadual'];
$ismunicipal = $_POST['ismunicipal'];
$codigoibge = $_POST['codigoibge'];
$regime = $_POST['regime'];

@$contato = limpaCPF_CNPJ($_POST['contato']);
@$contato2 = limpaCPF_CNPJ($_POST['contato2']);
@$cep = limpaCPF_CNPJ($_POST['cep']);
@$rua = AspasBanco($_POST['rua']);
@$bairro = AspasBanco($_POST['bairro']);
@$cidade = $_POST['cidade'];
@$estado = $_POST['estado'];


if (!empty($id) || !empty($nome) || !empty($contato)) {

  //atualizar campos comuns
  mysqli_query($conexao, "UPDATE user SET nome='$nome',fantasia='$fantasia',email='$email',cpf_cnpj='$cpf_cnpj',isestadual='$isestadual',ismunicipal='$ismunicipal',
    codigoibge='$codigoibge',regime='$regime',contato='$contato',contato2='$contato2',
    cep='$cep',rua='$rua',bairro='$bairro',cidade='$cidade',estado='$estado' WHERE id='$id'")
    or die(mysqli_error($conexao));

  echo update();
} else {
  echo persona('Campos obrigatórios!');
}
