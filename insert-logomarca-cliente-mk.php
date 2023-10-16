<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

@$id = $_POST['id'];
@$arquivologo = @$_POST['arquivologo'];
if($arquivologo != ''){
    //apagar antiga logo
    unlink("logocli/".@$arquivologo);
}
if(!empty(@$id)){    
    if($_FILES['arquivo']['name'] != ''){

        $diretorio = "logocli/";
        $extensao = strrchr($_FILES['arquivo']['name'],'.');
        $novonome = mb_strtolower(md5(uniqid(rand(), true)).$extensao);
    
        $filename = $_FILES['arquivo']['tmp_name'];
        $width = 150;
        $height = 150;
        list($width_orig, $height_orig) = getimagesize($filename);
        $ratio_orig = $width_orig/$height_orig;
        if ($width/$height > $ratio_orig) { $width = $height*$ratio_orig; } else { $height = $width/$ratio_orig; }
        $image_p = imagecreatetruecolor($width, $height);
    
        if($extensao == '.jpg' OR $extensao == '.png'){
    
            if($extensao == '.jpg'){
                $image = imagecreatefromjpeg($filename);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                imagejpeg($image_p, $diretorio.$novonome, 8);
            }
        
            if($extensao == '.png'){
                $image = imagecreatefrompng($filename);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                imagepng($image_p, $diretorio.$novonome, 8);
            }
        
            mysqli_query($conexao,"UPDATE user SET logomarca='$novonome' WHERE id='$id'") or die (mysqli_error($conexao));
            
            echo update();

        }else{
            echo persona('Apenas imagem .jp ou .png');
        }
    }
}else{
    echo persona('Algo deu errado :( !');
}
