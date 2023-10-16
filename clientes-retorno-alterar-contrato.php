<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$usercargo = $_SESSION['cargo'];
@$iduser = $_SESSION['iduser'];
@$iduser = $_SESSION['iduser'];
@$tipouser = $_SESSION['tipouser'];
if(isset($_SESSION['iduser'])!=true AND isset($_SESSION['situacaouser'])!=true){echo '<script>location.href="sair.php";</script>'; }

echo'
    <input type="hidden" name="idcliente" value="'.@$_GET['id'].'"/>
    <label class="col-xs-12">Contratos
    <select type="text" class="form-control" name="idcontrato" required>
    <option vaue="">selecione</option>';
    $query2 = mysqli_query($conexao,"SELECT contratos.*, user.nome as nomeempresa, cliente.nome as nomecliente FROM contratos
        LEFT JOIN user ON contratos.idempresa = user.idempresa 
        LEFT JOIN cliente ON contratos.idcliente = cliente.id         
        WHERE contratos.idempresa='$idempresa' AND contratos.idcliente=0") or die (mysqli_error($conexao));
      while($dd2 = mysqli_fetch_array($query2)){ 
      if($dd2['idcliente'] != ''){ $nomecliente = $dd2['nomecliente']; }else{ $nomecliente = '####### Sem cliente #######'; }

        echo'<option value="'.$dd2['id'].'">Cliente: '.$nomecliente.' | Plano: '.$dd2['nomeplano'].' | Login: '.$dd2['login'].' | Endereço:'.$dd2['rua'].','.$dd2['numero'].' | Bairro:'.$dd2['bairro'].'</option>';
    } echo'       
    </select>
    </label>
';
?>