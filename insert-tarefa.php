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

@$id = $_GET['id'];
@$area = $_POST['area'];
@$descricao = AspasBanco($_POST['descricao']);


if($id == '' AND !empty($area) || !empty($descricao)){
    
    mysqli_query($conexao,"INSERT INTO dev
    (area,descricao,data)
    VALUES ('$area','$descricao',NOW())") or die (mysqli_error($conexao));

    $idNovo = mysqli_insert_id($conexao);

        //img2
        if($_FILES['arquivo']['name'] != ''){
            $diretorio = "img-dev/";
            $extensao = strrchr($_FILES['arquivo']['name'],'.');
            $novonome = mb_strtolower(md5(uniqid(rand(), true)).'.jpeg');    
            $filename = $_FILES['arquivo']['tmp_name'];
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
              mysqli_query($conexao,"UPDATE dev SET img='$novonome' WHERE id='$idNovo'") or die (mysqli_error($conexao));
            }
        }

    echo insert();
    
}elseif($id != ''){

    mysqli_query($conexao,"DELETE FROM dev WHERE id='$id'") or die (mysqli_error($conexao));
    echo delete();

}else{
    echo persona('Erro inesperado!');
}

?>