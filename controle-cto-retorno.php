<?php
session_start();
include('conexao.php');
include('funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario']; //pega usuario que est� executando a a��o
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if (isset($_SESSION['iduser']) != true || empty($_SESSION['iduser'])) {
    echo '<script>location.href="sair.php";</script>';
}

$query = mysqli_query($conexao, "SELECT * FROM controle_cto WHERE id='$_GET[id]' AND idempresa='$idempresa'") or die(mysqli_error($conexao));
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_array($query);
    echo '
    <div class="row">
    <input type="hidden" name="id" value="' . $row['id'] . '"/>
    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">CTO
        <select type="text" class="form-control" name="cto">';
    if ($row['cto'] != '') {
        echo '<option value="' . $row['cto'] . '">' . $row['cto'] . '</option>';
    } else {
        echo '<option value="">CTO</option>';
    }
    $queryCto = mysqli_query($conexao, "SELECT cto FROM controle_cto WHERE idempresa='$idempresa' GROUP BY cto ORDER BY cto ASC");
    if (mysqli_num_rows($queryCto) > 0) {
        while ($r = mysqli_fetch_array($queryCto)) {
            echo '<option value="' . $r['cto'] . '">' . $r['cto'] . '</option>';
        }
    }
    echo '
        </select>
    </label>
    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">CTO Nova
        <input type="text" class="form-control" placeholder="Nova CTO" name="novacto"/>
    </label>
    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Porta
        <input type="text" class="form-control" placeholder="Porta" name="porta" value="' . $row['porta'] . '"/>
    </label>
    <!-- empresa cto porta cliente longitude latitude estado localizacao Nome -->
    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Empresa
        <input type="text" class="form-control" placeholder="Empresa" name="empresa" value="' . $row['empresa'] . '"/>
    </label>
    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Cliente
        <input type="text" class="form-control" placeholder="Cliente" name="cliente" value="' . $row['cliente'] . '"/>
    </label>
    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Localização/Endereço
        <input type="text" class="form-control" placeholder="Próx.Venda José" name="localizacao" value="' . $row['localizacao'] . '"/>
    </label>
    <label class="col-lg-6 col-md-12 col-sm-12 col-xs-12">Estado
        <select type="text" class="form-control" name="estado">';
    if ($row['estado'] != '') {
        echo '<option value="' . $row['estado'] . '">' . $row['estado'] . '</option>';
    } else {
        echo '<option value="">Estado</option>';
    }
    foreach ($estadosbr as $mes) {
        echo '<option value="' . $mes . '">' . $mes . '</option>';
    }
    echo '
        </select>
    </label>
    <label class="col-lg-6 col-md-6 col-sm-12 col-xs-6">Longitude
        <input type="text" class="form-control" name="longitude" value="' . $row['longitude'] . '"/>
    </label>
    <label class="col-lg-6 col-md-6 col-sm-12 col-xs-6">Latitude
        <input type="text" class="form-control" name="latitude" value="' . $row['latitude'] . '"/>
    </label>
</div>  ';
}
