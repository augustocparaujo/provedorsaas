<?php
session_start();
include('conexao.php');
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

if (file_exists("tabelas/" . @$idempresa . '.csv')) {
    if (($open = fopen("tabelas/" . @$idempresa . '.csv', "r")) !== FALSE) {
        $row = 0;
        while (($data = fgetcsv($open, 10000, ",")) !== FALSE) {
            $array[] = $data;
            if ($row > 1) {

                $tipo = $data[0];
                $nome = $data[1];
                $cpf = $data[2];
                $cnpj = $data[3];
                $fantasia = $data[4];
                $ie = $data[5];
                $rg = $data[6];
                $nascimento = $data[7];
                $contato = $data[8];
                $email = $data[9];
                $cep = $data[10];
                $rua = $data[11];
                $numero = $data[12];
                $bairro = $data[13];
                $municipio = $data[14];
                $estado = $data[15];
                $complemento = $data[16];
                $ativacao = $data[17];
                $vencimento = $data[18];

                $query = mysqli_query($conexao, "SELECT * FROM cliente WHERE nome='$nome'");
                if (mysqli_num_rows($query) == 0) {
                    mysqli_query($conexao, "INSERT INTO cliente (idempresa,tipo,nome,cpf,cnpj,fantasia,ie,rg,nascimento,contato,email,cep,rua,numero,
                    bairro,municipio,estado,complemento,ativacao,vencimento,situacao,usuariocad,data) 
                    VALUES ('$idempresa','$tipo','$nome','$cpf','$cnpj','$fantasia','$ie','$rg','$nascimento','$contato','$email','$cep','$rua','$numero',
                    '$bairro','$municipio','$estado','$complemento','$ativacao','$vencimento','ativo','$nomeuser',NOW())");
                }
            }
            $row++;
        }
        fclose($open);

        echo persona('Dados importado com sucesso', 'success');
    }
} else {
    echo persona('Erro inesperado !', 'danger');
}
