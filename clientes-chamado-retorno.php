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
$id = $_GET['idcliente']; 
echo'
    <div class="col-lg-12">
    <input type="text" class="form-control hidden" name="idcliente" value="'.$id.'"/>
    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Tipo
    <select type="text" class="form-control" name="tipo" required>
        <option vaue="INCIDENTE">INCIDENTE</option>
        <option value="INSTALAÇÃO">INSTALAÇÃO</option>
        <option value="SOLICITAÇÃO">SOLICITAÇÃO</option>
    </select>
    </label>
    <div class="row"></div><br />
    <label class="col-xs-12">Contrato
    <select type="text" class="form-control" name="idcontrato" required>
    <option vaue="">selecione</option>';
    $query2 = mysqli_query($conexao,"SELECT * FROM contratos WHERE idcliente='$id'") or die (mysqli_error($conexao));
    while($dd2 = mysqli_fetch_array($query2)){ 
        echo'<option value="'.$dd2['id'].'-'.$dd2['nomeplano'].'">Plano: '.$dd2['nomeplano'].' - N° contrato: '.$dd2['id'].'- Endereço:'.$dd2['rua'].','.$dd2['numero'].' - Bairro:'.$dd2['bairro'].'</option>';
    } echo'       
    </select>
    </label>
    <div class="row"></div><br />
    <label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrição
        <textarea rows="3" class="form-control" placeholder="Descrição" name="obs"></textarea>
    </label>
    <div class="row"></div>
    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Data para atendimento
        <input type="date" class="form-control" name="dataatendimento" required/>
    </label>
    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Situação
    <select type="text" class="form-control" name="situacao" required>
        <option vaue="ABERTO">ABERTO</option>
        <option value="SOLUCIONADO">SOLUCIONADO</option>
        <option value="PENDENTE">PENDENTE</option>
        <option value="PENDENTE TERCEIRO">PENDENTE TERCEIRO</option>
        <option value="CANCELADO">CANCELADO</option>
    </select>
    </label>
    <div class="row"></div><hr>
    <ol style="font-size:12px">
        <li>INCIDENTE: Problema em equipamento, rompimento de cabo, etc</li>
        <li>INSTALAÇÃO: Agendamento de instalação de equipamento, assinatura, etc</li>
        <li>SOLICITAÇÃO: Troca de senha wifi, mudança de vencimento, mudança de plano, etc</li>
        <li>PENDENTE TERCEIRO: falta de energia no local, não tem por onde passar cabo da fibra, etc</li>
    </ol>
    </div>';
