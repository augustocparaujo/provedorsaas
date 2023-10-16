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
@$id = $_GET['id']; 

$query0 = mysqli_query($conexao,"SELECT * FROM chamado WHERE id='$id'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($query0);
$idcliente = $dd['idcliente'];

$query2 = mysqli_query($conexao,"SELECT * FROM contratos WHERE idcliente='$idcliente'") or die (mysqli_error($conexao));

echo'
    <div class="col-lg-12">
    <input type="text" class="form-control hidden" name="id" value="'.@$id.'"/>
    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Tipo
    <select type="text" class="form-control" name="tipo" required>
    <option value="'.$dd['tipo'].'">'.$dd['tipo'].'</option>
        <option vaue="INCIDENTE">INCIDENTE</option>
        <option value="INSTALAÇÃO">INSTALAÇÃO</option>
        <option value="SOLICITAÇÃO">SOLICITAÇÃO</option>
    </select>
    </label>
<div class="row"></div><br />
    <label class="col-xs-12">Contrato
    <select type="text" class="form-control" id="buscaendereco" name="idcontrato" required>';
    if($dd['idcontrato'] != ''){ echo '<option vaue="'.$dd['idcontrato'].'">'.$dd['idcontrato'].'</option>';}else{echo'<option value="">selecione</option>';}
    while($dd2 = mysqli_fetch_array($query2)){ echo'<option value="'.$dd2['id'].'-'.$dd2['nomeplano'].'">Plano:'.$dd2['nomeplano'].' - N° contrato: '.$dd2['id'].' - Endereço:'.$dd2['rua'].','.$dd2['numero'].' - Bairro:'.$dd2['bairro'].'</option>';} echo'       
    </select>
    </label>
    <div class="row"></div><br />
    <label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrio
        <textarea rows="3" class="form-control" placeholder="Descrição" name="obs">'.AspasForm($dd['obs']).'</textarea>
    </label>
    <div class="row"></div>
    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Data para atendimento
        <input type="date" class="form-control" name="dataatendimento" value="'.date($dd['dataatendimento']).'" required/>
    </label>
    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Situação
    <select type="text" class="form-control" name="situacao" required>
        <option value="'.$dd['situacao'].'">'.$dd['situacao'].'</option>
        <option vaue="ABERTO">ABERTO</option>
        <option value="SOLUCIONADO">SOLUCIONADO</option>
        <option value="PENDENTE">PENDENTE</option>
        <option value="PENDENTE TERCEIRO">PENDENTE TERCEIRO</option>
        <option value="CANCELADO">CANCELADO</option>
    </select>
    </label>
    <div class="row"></div><hr>

    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Técnico
    <select type="text" class="form-control" name="idtecnico">
    <option vaue="'.$dd['idtecnico'].'">'.$dd['nometecnico'].'</option>';
    $query3 = mysqli_query($conexao,"SELECT * FROM usuarios WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
    while($dd3 = mysqli_fetch_array($query3)){ echo'<option value="'.$dd3['id'].'">'.$dd3['nome'].'</option>';} echo'       
    </select>
    </label>

    </div>';
?>