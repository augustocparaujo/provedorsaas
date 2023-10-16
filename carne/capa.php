<?php
session_start();
include('../conexao.php'); 
include('../funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$logomarcauser = $_SESSION['logomarcauser'];
@$iduser = $_SESSION['iduser'];
@$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
@$usercargo = $_SESSION['cargo'];
@$situacaouser = $_SESSION['situacaouser'];
@$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
@$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true || empty($_SESSION['iduser'])){echo '<script>location.href="sair.php";</script>'; }

$query = mysqli_query($conexao,"SELECT * FROM user WHERE idempresa='$idempresa'") or die (mysqli_error($conexao));
$dd = mysqli_fetch_array($query);
//cliente
$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT cliente.nome,fantasia,rua,numero,bairro,municipio,estado,cep,cpf,cnpj FROM cliente WHERE id='$id'") or die (mysqli_error($conexao));
$ret = mysqli_fetch_array($query);

?>
<!DOCTYPE HTML>
<!-- SPACES 2 -->
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="Resource-type" content="document">
    <meta name="Robots" content="all">
    <meta name="Rating" content="general">
    <meta name="author" content="Gabriel Masson">
    <title>Capa do Carnê</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
  <div class="bto">
    Ao Imprimir o carnê certifique-se se a impressão está ajustada à página
    <br>
    <br>
    <button class="btn-impress" onclick="window.print()">Imprimir</button>
  </div>

  <div class="capa">
    <div class="grid">
      <div class="col2 text-center"><?php
      if(!empty($dd['logomarca'])){ echo '<img src="../logocli/'.$dd['logomarca'].'" style="border-radius:45%"/>';} else { echo '<i class="text-red">sem logomarca</i>'; } 
      ?>
      </div>
      <div class="col5">
        <h1>Carnê de Pagamento</h1>
        <p>
        <?php if($dd['fantasia'] != ''){ echo'<strong>'.strtoupper($dd['fantasia']).'</strong><br>'; }else{ echo'<strong>'.strtoupper($dd['nome']).'</strong><br>'; } ?>
        <strong>Endereço:</strong> <?php echo @$dd['rua']; ?>
        <br><strong>Bairro:</strong> <?php echo @$dd['bairro'].' - '.@$dd['cidade'].'/'.@$dd['estado']; ?> 
        <br><strong>Whatsapp:</strong> <?php echo @$dd['contato']; ?>
        <br><strong>Telefone:</strong> <?php echo @$dd['contato2']; ?>
        </p>
      </div>
       <div class="col5">
        <h1>Cliente</h1>
        <p>
        <?php if($ret['fantasia'] != ''){ echo'<strong>'.strtoupper($ret['fantasia']).'</strong><br>'; }else{ echo'<strong>'.strtoupper($ret['nome']).'</strong><br>'; } ?>
        <strong>Endereço:</strong> <?php echo @$ret['rua']; ?>
        <br><strong>Bairro:</strong> <?php echo @$ret['bairro'].' - '.@$ret['cidade'].'/'.@$ret['estado']; ?> 
        <br><strong>Whatsapp:</strong> <?php echo @$ret['contato']; ?>
        <br><strong>Telefone:</strong> <?php echo @$ret['contato2']; ?>
        </p>
      </div>
    </div>
  </div>

  </body>
</html>