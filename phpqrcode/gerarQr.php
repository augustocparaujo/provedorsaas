<?php    

function qrCode($data,$name){
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'../imgqrcode/'.DIRECTORY_SEPARATOR;
    include "qrlib.php";    
    // user data
    $name2 = $name.'.png';
    $filename = $PNG_TEMP_DIR.$name2;
    QRcode::png($data, $filename, 'L', 4, 2);         
    //echo $name2;  
}