<?php
if (!empty(@$_POST['idempresa'])) {
        include_once('conexao.php');
        include_once('funcoes.php');

        $idempresa2 = $_POST['idempresa'];
        $nome = AspasBanco($_POST['nome']);
        $contato = limpaCPF_CNPJ($_POST['contato']);
        $vencimento = intval($_POST['vencimento']);
        $plano = AspasBanco($_POST['plano']);

        mysqli_query($conexao, "INSERT INTO cliente (idempresa,nome,contato,vencimento,obs) 
        VALUES ('$idempresa2','$nome','$contato','$vencimento','$plano')") or die(mysqli_error($conexao));

       echo "<script>window.location='obrigado.php?id=$idempresa2'</script>";
} else {
        echo "<script>window.location='https://google.com.br'</script>";
}
