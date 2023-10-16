<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
$idempresa = $_SESSION['idempresa'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$logomarca = $_SESSION['logomarcauser'];
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina

$dia = $_GET['id'];
$data = date('d/m/Y');

// chamando os arquivos necessários do DOMPdf
require_once 'gerarpdf/lib/html5lib/Parser.php';
require_once 'gerarpdf/lib/font/src/FontLib/Autoloader.php';
require_once 'gerarpdf/lib/svg/src/autoload.php';
require_once 'gerarpdf/src/Autoloader.php';

// definindo os namespaces
Dompdf\Autoloader::register();
use Dompdf\Dompdf;

// inicializando o objeto Dompdf
$dompdf = new Dompdf();

// coloque nessa variável o código HTML que você quer que seja inserido no PDF
$codigo_html="
<link type=text/css href=gerarpdf/style.css rel=stylesheet/>
<div class=geral>
<div class=corpo>
    <div class=topo>
    <div class=imagem>
        <img src='logocli/".$logomarca."'/>
    </div>
    <div class=cabecario>
        <h5><b>RELATÓRIO DE CLIENTE <br> VENCIMENTO DIA:".$dia."<br> DATA: ".$data."</b></h5>
    </div>
    </div>

<table class=fontpequena>
    <tr class=thcabeca>
        <th>Código</th>
        <th align=left>Nome</th>
        <th align=left>CPF/CNPJ</th>
        <th align=left>Plano</th>
        <th align=left>Vencimento</th>   
        <th align=left>Situção</th>        
    </tr>";
    $query = mysqli_query($conexao,"SELECT cliente.*, plano.plano AS nomeplano FROM cliente 
    LEFT JOIN plano ON cliente.plano = plano.id
    WHERE cliente.vencimento LIKE '$dia%' AND cliente.idempresa='$idempresa' 
    AND plano.idempresa='$idempresa' 
    ORDER BY cliente.nome ASC") or die (mysqli_error($conexao));
    while($item = mysqli_fetch_array($query)){
        $codigo_html.="<tr>
            <td>".$item['id']."</td>
            <td>".$item['nome']."</td>
            <td>".@$item['cpf']." ".@$item['cnpj']."</td>
            <td>".$item['nomeplano']."</td>
            <td>".$item['vencimento']."</td>
            <td>".$item['situacao']."</td>
        </tr>";    }
$codigo_html.="</table>
</div>
    <div class=rodape><img src='gisp-logo.png'/></div>
</div>";

// carregamos o código HTML no nosso arquivo PDF
$dompdf->loadHtml($codigo_html);

// (Opcional) Defina o tamanho (A4, A3, A2, etc) e a oritenação do papel, que pode ser 'portrait' (em pé) ou 'landscape' (deitado)
$dompdf->setPaper('A4', 'portrait');

// Renderizar o documento
$dompdf->render();

// pega o código fonte do novo arquivo PDF gerado
$output = $dompdf->output();

// defina aqui o nome do arquivo que você quer que seja salvo
file_put_contents("1.pdf", $output);

// redirecionamos o usuário para o download do arquivo
die("<script>window.open('1.pdf');</script>");


?>