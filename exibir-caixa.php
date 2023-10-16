<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];

if(!empty($_GET['id'])){
    $id = $_GET['id'];
    $query = mysqli_query($conexao,"SELECT * FROM caixa WHERE id='$id' AND idempresa='$idempresa'") or die (mysqli_error($conexao));
    $dd = mysqli_fetch_array($query);
    echo'
        <label class="col-lg-12 col-md-12 col-sm-12">Valor correto
            <input type="text" class="form-control real"  name="valor" value="'.Real(@$dd['valor']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Cartão Crédito
            <input type="text" class="form-control real"  name="cartaocredito" value="'.Real(@$dd['cartaocredito']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Cartão débito
            <input type="text" class="form-control real"  name="cartaodebito" value="'.Real(@$dd['cartaodebito']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">PIX
            <input type="text" class="form-control real"  name="pix" value="'.Real(@$dd['pix']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Dinheiro
            <input type="text" class="form-control real"  name="dinheiro" value="'.Real(@$dd['dinheiro']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Valor recebido
            <input type="text" class="form-control real"  name="valorpago" value="'.Real(@$dd['valorpago']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Data recebido
            <input type="text" class="form-control data"  name="datapagamento" value="'.dataForm(@$dd['datapagamento']).'" readonly/>
        </label>';
}elseif(!empty($_GET['titulo'])){
    $titulo = $_GET['titulo'];
    $query = mysqli_query($conexao,"SELECT * FROM caixa WHERE titulo='$titulo' AND idempresa='$idempresa'") or die (mysqli_error($conexao));
    $dd = mysqli_fetch_array($query);
    echo'
        <label class="col-lg-12 col-md-12 col-sm-12">Valor correto
            <input type="text" class="form-control real"  name="valor" value="'.Real(@$dd['valor']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Cartão Crédito
            <input type="text" class="form-control real"  name="cartaocredito" value="'.Real(@$dd['cartaocredito']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Cartão débito
            <input type="text" class="form-control real"  name="cartaodebito" value="'.Real(@$dd['cartaodebito']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">PIX
            <input type="text" class="form-control real"  name="pix" value="'.Real(@$dd['pix']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Dinheiro
            <input type="text" class="form-control real"  name="dinheiro" value="'.Real(@$dd['dinheiro']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Valor recebido
            <input type="text" class="form-control real"  name="valorpago" value="'.Real(@$dd['valorpago']).'" readonly/>
        </label>
        <label class="col-lg-12 col-md-12 col-sm-12">Data recebido
            <input type="text" class="form-control data"  name="datapagamento" value="'.dataForm(@$dd['datapagamento']).'" readonly/>
        </label>';
}else{
    echo'<label class="col-lg-12 col-md-12 col-sm-12">Sem registro</label>';
}
?>
<!-- mascaras -->
<script src="dist/js/jquery.mask.js"></script>
<script src="dist/js/jquery.maskMoney.js"></script>
<script src="dist/js/meusscripts.js"></script>