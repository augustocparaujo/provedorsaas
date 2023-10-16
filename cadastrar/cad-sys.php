<?php
session_start();
include('../conexao.php');
include('../funcoes.php');
$ip = AspasBanco($_SERVER['REMOTE_ADDR']);

@$nome = AspasBanco($_POST['nome']);
@$email = AspasBanco($_POST['email']);
@$cpf = $_POST['cpf'];
@$user = md5($_POST['cpf']);
@$senha = md5($_POST['senha']);
@$idempresa = date('YmdHms') . mt_rand(5, 15);

if (!empty($nome) and !empty($user) and !empty($email) and !empty($senha)) {

    //
    $sql = mysqli_query($conexao, "SELECT email,cpf_cnpj,ip FROM user WHERE email='$email' and cpf_cnpj='$cpf' and ip='$ip'");
    if (mysqli_num_rows($sql) == 0) {

        try {

            mysqli_query($conexao, "INSERT INTO user (idempresa,tipo,nome,cpf_cnpj,user,senha,datacadastro,usuariocad,situacao,ip) 
            VALUES ('$idempresa','Admin','$nome','$cpf','$user','$senha',NOW(),'cadastro site',1,'$ip')");

            echo '<div class="input-group">
                <button class="btn-success" style="background:##32CD32">Cadastro realizado com sucesso!</button>
                </div>';
        } catch (mysqli_sql_exception $e) {
            echo '<div class="input-group">
        <button class="btn-danger" style="background:#FF0000">Cadastro já realizado!</button>
    </div>';
        }
    } else {
        echo '<div class="input-group">
        <button class="btn-danger" style="background:#FF0000">Cadastro já realizado!</button>
    </div>';
    }
} else {

    echo '<div class="input-group">
    <button class="btn-danger" style="background:#FF0000">Preencher campos obrigatórios!!</button>
</div>';
}
