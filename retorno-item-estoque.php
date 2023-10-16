<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }

$id = @$_GET['id'];
$query = mysqli_query($conexao,"SELECT j_estoque.*, j_categoria_estoque.nome_cat AS categoria1, j_fornecedor_equip.descricao AS fornecedor1 FROM j_estoque 
LEFT JOIN j_categoria_estoque ON j_estoque.categoria = j_categoria_estoque.id
LEFT JOIN j_fornecedor_equip ON j_estoque.fornecedor = j_fornecedor_equip.id
WHERE j_estoque.id='$id'") or die (mysqli_error($conexao));
$ret = mysqli_fetch_array($query);
echo'
<div class="row">
<input type="text" style="display:none" name="id" value="'.$id.'"/>
<div class="col-lg-12">
<label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Categoria
<select type="text" class="form-control" name="categoria">';
    if($ret['categoria'] != ''){ echo'<option value="'.$ret['categoria'].'">'.$ret['categoria1'].'</option>'; }else{ echo'<option value="">selecione</option>'; }
    $query = mysqli_query($conexao,"SELECT * FROM j_categoria_estoque ORDER BY nome_cat ASC") or die (mysqli_error($conexao));
    if(mysqli_num_rows($query) >= 1){
        while($ret1 = mysqli_fetch_array($query)){
            if($ret1['id'] != $ret['categoria']){
            echo'<option value="'.$ret1['id'].'">'.$ret1['nome_cat'].'</option>';
            }
        }
    }
echo'
</select>
</label>

<label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Forncedor
<select type="text" class="form-control" name="fornecedor">';
if($ret['fornecedor'] != ''){ echo'<option value="'.$ret['fornecedor'].'">'.$ret['fornecedor1'].'</option>'; }else{ echo'<option value="">selecione</option>'; }
$query2 = mysqli_query($conexao,"SELECT * FROM j_fornecedor_equip ORDER BY descricao ASC") or die (mysqli_error($conexao));
    if(mysqli_num_rows($query2) >= 1){
        while($ret2 = mysqli_fetch_array($query2)){
            if($ret2['id'] != $ret['fornecedor']){
            echo'<option value="'.$ret2['id'].'">'.$ret2['descricao'].'</option>';
            }
        }
    }
echo'
</select>
</label>

<label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrição
  <input type="text" class="form-control" placeholder="Descrição" name="descricao" value="'.AspasForm($ret['descricao']).'"/>
</label>

<label class="col-xs-12 col-lg-6 col-md-12 col-sm-12">Quantidade
  <input type="number" class="form-control" placeholder="Quantidade" name="quantidade" value="'.$ret['quantidade'].'"/>
</label>

</div>

</div>';

?>