<?php
session_start();
include('../conexao.php');
include('../funcoes.php');
$idempresa = $_SESSION['idempresa'];

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conexao, "SELECT * FROM cobranca WHERE id='$id' AND idempresa='$idempresa'") or die(mysqli_error($conexao));
    $dd = mysqli_fetch_array($query);
    if (!empty($dd['qrcode2'])) {
        $aqui = $dd['qrcode2'];
    } else {
        $aqui = 'Sem qrcode';
    }
    echo '
    <center>
        <label class="col-12">
            <textarea rows="12" class="form-control" id="codigoPix" name="pix" readonly>' . $aqui . '</textarea>
        </label>
        <label class="col-12">
            <button onclick="copiarPix()" class="btn btn-primary btn-block"><i class="fa fa-copy fa-2x"></i> Cópiar código</button>
        </label></center>';
}
?>
<!-- mascaras -->
<script src="../dist/js/jquery.mask.js"></script>
<script src="../dist/js/jquery.maskMoney.js"></script>
<script src="../dist/js/meusscripts.js"></script>