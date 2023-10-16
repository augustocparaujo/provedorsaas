<?php
session_start();
include('../conexao.php');

session_destroy();
session_unset();
ob_end_clean(); // J� podemos encerrar o buffer e limpar tudo que h� nele
echo "<script>location.href='login.php'</script>";
