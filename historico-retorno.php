<?php

session_start();
include('conexao.php'); 
include('funcoes.php');
include_once('routeros_api.class.php');
@$idempresa = $_SESSION['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$iduser = $_SESSION['iduser'];
@$iduser = $_SESSION['iduser'];
@$tipouser = $_SESSION['tipouser'];

if(isset($_SESSION['iduser'])!=true AND isset($_SESSION['situacaouser'])!=true){echo '<script>location.href="sair.php";</script>'; }

@$id = $_GET['id']; 
$query = mysqli_query($conexao,"SELECT * FROM historico WHERE id='$id'") or die (mysqli_error($conexao));
$ret = mysqli_fetch_array($query);
echo'
	<div class="col-lg-12">
        <input type="text" class="form-control hidden" name="id" value="'.@$ret['id'].'"/>
        <label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrição
            <textarea rows="3" class="form-control" placeholder="Descrição" name="obs"></textarea>
        </label>
  </div>';
  ?>