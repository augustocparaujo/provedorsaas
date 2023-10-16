<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a�o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

$nchamado = $_POST['nchamado'];
$obs = AspasBanco($_POST['obs']);
$situacao = $_POST['situacao'];
$dataatendimento0 = dataForm($_POST['dataatendimento0']);
if($_POST['dataatendimento'] != ''){ 
    $dataatendimento = date($_POST['dataatendimento']); 
    $obs = 'Data atendimento alterada de: '.$dataatendimento0.'<br />'.'Data atendimento alterada para: '.$dataatendimento.'<br />'.$obs;
} else { 
    $dataatendimento = ''; 
}

if(!empty($nchamado) AND !empty($_POST['obs'])){
    mysqli_query($conexao,"INSERT INTO log_chamado (nchamado,usuariocad,datacad,obs,situacao) 
    VALUES('$nchamado','$nomeuser',NOW(),'$obs','$situacao')") or die (mysqli_error($conexao));
  	$idNovo = mysqli_insert_id($conexao);
    //img2
    if($_FILES['imgRetorno']['name'] != ''){
        $diretorio = "docchamado/";
        $extensao = strrchr($_FILES['imgRetorno']['name'],'.');
        $novonome = mb_strtolower(md5(uniqid(rand(), true)).'.jpeg');    
        $filename = $_FILES['imgRetorno']['tmp_name'];
        $width = 1500;
        $height = 1500;
        list($width_orig, $height_orig) = getimagesize($filename);
        $ratio_orig = $width_orig/$height_orig;
        if ($width/$height > $ratio_orig) { $width = $height*$ratio_orig; } else { $height = $width/$ratio_orig; }
        $image_p = imagecreatetruecolor($width, $height);    
        if($extensao == '.jpg' OR $extensao == '.jpeg' OR $extensao == '.JPG' OR $extensao == '.JPEG'){       
          $image = imagecreatefromjpeg($filename);
          imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
          imagejpeg($image_p, $diretorio.$novonome, 99);              
          mysqli_query($conexao,"UPDATE log_chamado SET imgRetorno='$novonome' WHERE id='$idNovo'") or die (mysqli_error($conexao));
        }
    }
  //pdf
  if($_FILES['docRetorno']['name'] != ''){
        $diretorio = "docchamado/";
        $extensao = strrchr($_FILES['docRetorno']['name'],'.');
        $novonome = mb_strtolower(md5(uniqid(rand(), true)).'.pdf');
        $arquivo_tmp = $_FILES['docRetorno']['tmp_name']; 
    	move_uploaded_file($arquivo_tmp, $diretorio.$novonome);
        mysqli_query($conexao,"UPDATE log_chamado SET docRetorno='$novonome' WHERE id='$idNovo'") or die (mysqli_error($conexao));
  }
  

    mysqli_query($conexao,"UPDATE chamado SET situacao='$situacao' WHERE nchamado='$nchamado'") or die (mysqli_error($conexao));
    if($_POST['dataatendimento'] != ''){ 
        mysqli_query($conexao,"UPDATE chamado SET dataatendimento='$dataatendimento' WHERE nchamado='$nchamado") or die (mysqli_error($conexao));
     }

    echo insert();
    
}else{
    echo persona('Preencher campos obrigatórios.');
} 

?>