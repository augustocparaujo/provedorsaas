<?php
//permissao atualzia��o //recebe informa��es vindas do array de permiss�o
function Permissao($idempresa, $item, $id, $iduser)
{
    include('conexao.php');
    $hoje0 = date('Y-m-d');
    $sql = mysqli_query($conexao, "SELECT * FROM permissao WHERE idempresa='$idempresa' AND usuario='$id' AND item='$item'") or die(mysqli_error($conexao));;
    if (mysqli_num_rows($sql) >= 1) {
        mysqli_query($conexao, "UPDATE permissao SET valor='ativo',datacad='$hoje0',usuariocad='$iduser' WHERE idempresa='$idempresa' AND usuario='$id' AND item='$item'") or die(mysqli_error($conexao));
    } else {
        mysqli_query($conexao, "INSERT INTO permissao (idempresa,usuario,item,valor,datacad,usuariocad) VALUES ('$idempresa','$id','$item','ativo',NOW(),'$iduser')") or die(mysqli_error($conexao));
    }
};

//verifica permissões
function PermissaoCheck($idempresa, $item, $id)
{
    include('conexao.php');
    $sql1 = mysqli_query($conexao, "SELECT * FROM permissao 
    WHERE idempresa='$idempresa' AND usuario='$id' AND item='$item' AND valor='ativo'") or die(mysqli_error($conexao));
    if (mysqli_num_rows($sql1) >= 1) {
        return 'checked';
    }
};

//fun��o limpa ponto e tra�o

function limpaCPF_CNPJ($valor)
{
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    $valor = str_replace("(", "", $valor);
    $valor = str_replace(")", "", $valor);
    $valor = str_replace(" ", "", $valor);
    $valor = str_replace("<", "", $valor);
    $valor = str_replace(">", "", $valor);
    $valor = str_replace("@", "", $valor);
    $valor = str_replace("#", "", $valor);
    $valor = str_replace("%", "", $valor);
    $valor = str_replace("'", "", $valor);
    return $valor;
};
//limpar
function limpar($valor)
{
    $valor = trim($valor);

    return $valor;
};

function Moeda($get_valor)
{
    $source = array('.', ',');
    $replace = array('', '.');
    $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
    if (empty($valor)) {
        return 0;
    } else {
        return $valor;
    } //retorna o valor formatado para gravar no banco
}; //moeda

function Moeda2($valor)
{
    $valor = number_format($valor, 2);
    $source = array(',', '.');
    $replace = array('.', '');
    $valor = str_replace($source, $replace, $valor);
    return $valor;
}; //moeda2

function Real($valor)
{
    if ($valor == true) {
        return number_format($valor, 2, ',', '.');
    } else {
        return '0,00';
    }
};


//case de meses por valor

switch (date("m")) {
    case "01":
        @$mes = 'Janeiro';
        break;
    case "02":
        @$mes = 'Fevereiro';
        break;
    case "03":
        @$mes = 'Março';
        break;
    case "04":
        @$mes = 'Abril';
        break;
    case "05":
        @$mes = 'Maio';
        break;
    case "06":
        @$mes = 'Junho';
        break;
    case "07":
        @$mes = 'Julho';
        break;
    case "08":
        @$mes = 'Agosto';
        break;
    case "09":
        @$mes = 'Setembro';
        break;
    case "10":
        @$mes = 'Outubro';
        break;
    case "11":
        @$mes = 'Novembro';
        break;
    case "12":
        @$mes = 'Dezembro';
        break;
};

//idadeCerta
function idadeCerta($nascimento)
{
    // Declara a data! :P
    $data = $nascimento;
    // Separa em dia, m�s e ano
    list($dia, $mes, $ano) = explode('-', $data);
    // Descobre que dia � hoje e retorna a unix timestamp
    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    // Descobre a unix timestamp da data de nascimento do fulano
    $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);
    // Depois apenas fazemos o c�lculo j� citado :)
    $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
    return $idade;
};

function AspasForm($string)
{
    $string = @str_replace('"', chr(146) . chr(146), $string);
    $string = @str_replace("'", chr(146), $string);
    return $string;
};

function AspasBanco($string)
{
    $string = @str_replace(chr(146) . chr(146), '"', $string);
    $string = @str_replace(chr(146), "'", $string);
    return @addslashes($string);
};

function url_amigavel($string)
{
    $table = array(
        '�' => 'S', '�' => 's', '�' => 'D', 'd' => 'd', '�' => 'Z',
        '�' => 'z', 'C' => 'C', 'c' => 'c', 'C' => 'C', 'c' => 'c',
        '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A',
        '�' => 'A', '�' => 'A', '�' => 'C', '�' => 'E', '�' => 'E',
        '�' => 'E', '�' => 'E', '�' => 'I', '�' => 'I', '�' => 'I',
        '�' => 'I', '�' => 'N', '�' => 'O', '�' => 'O', '�' => 'O',
        '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'U', '�' => 'U',
        '�' => 'U', '�' => 'U', '�' => 'Y', '�' => 'B', '�' => 'Ss',
        '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a',
        '�' => 'a', '�' => 'a', '�' => 'c', '�' => 'e', '�' => 'e',
        '�' => 'e', '�' => 'e', '�' => 'i', '�' => 'i', '�' => 'i',
        '�' => 'i', '�' => 'o', '�' => 'n', '�' => 'o', '�' => 'o',
        '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'u',
        '�' => 'u', '�' => 'u', '�' => 'y', '�' => 'y', '�' => 'b',
        '�' => 'y', 'R' => 'R', 'r' => 'r',
    );
    // Traduz os caracteres em $string, baseado no vetor $table
    $string = strtr($string, $table);
    // converte para min�sculo
    $string = strtolower($string);
    // remove caracteres indesej�veis (que n�o est�o no padr�o)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    // Remove m�ltiplas ocorr�ncias de h�fens ou espa�os
    $string = preg_replace("/[\s-]+/", " ", $string);
    // Transforma espa�os e underscores em h�fens
    $string = preg_replace("/[\s_]/", " ", $string);
    // retorna a string
    return $string;
}; //url_amigavel

function insert()
{
    echo '
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"> X</button>
        <strong><i class="icon fa fa-check"></i><strong> Salvo! 
    </div>';
};

function update()
{
    echo '
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"> X</button>
        <strong><i class="icon fa fa-warning"></i></strong> Atualizado!  
    </div>';
};

function delete()
{
    echo '
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"> X</button>
        <strong><i class="icon fa fa-ban"></i></strong> Excluido! 
    </div>';
};


function deletePersona($valor)
{
    echo '
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"> X</button>
        <strong><i class="icon fa fa-ban"></i></strong> ' . $valor . '
    </div>';
};

function persona($valor)
{
    echo '<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
    <i class="fa fa-exclamation-triangle"></i> <strong> ' . $valor . '</strong>
    </div>';
};

function persona2($valor)
{
    echo '<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
    <i class="icon fa fa-check"></i> <strong> ' . $valor . '</strong>
    </div>';
};

function gerar_senha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos)
{
    $senha = '';
    $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
    $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
    $nu = "0123456789"; // $nu contem os números
    $si = "!@#$%¨&*()_+="; // $si contem os símbolos

    if ($maiusculas) {
        // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($ma);
    }

    if ($minusculas) {
        // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($mi);
    }

    if ($numeros) {
        // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($nu);
    }

    if ($simbolos) {
        // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
        $senha .= str_shuffle($si);
    }

    // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
    return substr(str_shuffle($senha), 0, $tamanho);
}

//data form
function dataForm($valor)
{
    if ($valor != '0000-00-00') {
        $valor = date('d-m-Y', strtotime($valor));
        return $valor;
    } else {
        return '';
    }
}
//data certa
function dataBanco($valor)
{
    if ($valor != '0000-00-00') {
        $valor = date('Y-m-d', strtotime($valor));
        return $valor;
    }
}

function diasVencidos($data)
{
    // Calcula a diferença em segundos entre as datas
    $diferenca = strtotime(date('Y-m-d')) - strtotime($data);
    //Calcula a diferença em dias
    $dias = floor($diferenca / (60 * 60 * 24));
    if ($dias <= 0) {
        $dias = 'não vencido';
    }
    return $dias;
}

function primeiroNome($valor)
{
    $primeiroNome = @explode(" ", $valor);
    return $primeiroNome[0];
}

function gerarCobrancaSemBoleto($idempresa, $idcliente, $nomecliente, $vencimento, $valor)
{
    include('conexao.php');
    $codigocobranca = $idcliente . date('dmYHis');

    mysqli_query($conexao, "INSERT INTO cobranca (idempresa,idcliente,tipo,tipocobranca,ncobranca,cliente,vencimento,valor,situacao) 
    VALUES ('$idempresa','$idcliente','Manual','plano','$codigocobranca','$nomecliente','$vencimento','$valor','PENDENTE')") or die(mysqli_error($conexao));

    //return persona('Criado com sucesso');
}

//converter para megas
function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'Kbs', 'Mbs', 'Gbs', 'Tbs');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

/* por mauricio programador */
function geraCodigoBarra($numero)
{
    $fino = 1;
    $largo = 3;
    $altura = 50;

    $barcodes[0] = '00110';
    $barcodes[1] = '10001';
    $barcodes[2] = '01001';
    $barcodes[3] = '11000';
    $barcodes[4] = '00101';
    $barcodes[5] = '10100';
    $barcodes[6] = '01100';
    $barcodes[7] = '00011';
    $barcodes[8] = '10010';
    $barcodes[9] = '01010';

    for ($f1 = 9; $f1 >= 0; $f1--) {
        for ($f2 = 9; $f2 >= 0; $f2--) {
            $f = ($f1 * 10) + $f2;
            $texto = '';
            for ($i = 1; $i < 6; $i++) {
                $texto .= substr($barcodes[$f1], ($i - 1), 1) . substr($barcodes[$f2], ($i - 1), 1);
            }
            $barcodes[$f] = $texto;
        }
    }

    echo '<img src="carne/barras/p.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';
    echo '<img src="carne/barras/b.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';
    echo '<img src="carne/barras/p.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';
    echo '<img src="carne/barras/b.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';

    echo '<img ';

    $texto = $numero;

    if ((strlen($texto) % 2) <> 0) {
        $texto = '0' . $texto;
    }

    while (strlen($texto) > 0) {
        $i = round(substr($texto, 0, 2));
        $texto = substr($texto, strlen($texto) - (strlen($texto) - 2), (strlen($texto) - 2));

        if (isset($barcodes[$i])) {
            $f = $barcodes[$i];
        }

        for ($i = 1; $i < 11; $i += 2) {
            if (substr($f, ($i - 1), 1) == '0') {
                $f1 = $fino;
            } else {
                $f1 = $largo;
            }

            echo 'src="carne/barras/p.gif" width="' . $f1 . '" height="' . $altura . '" border="0">';
            echo '<img ';

            if (substr($f, $i, 1) == '0') {
                $f2 = $fino;
            } else {
                $f2 = $largo;
            }

            echo 'src="barras/b.gif" width="' . $f2 . '" height="' . $altura . '" border="0">';
            echo '<img ';
        }
    }
    echo 'src="carne/barras/p.gif" width="' . $largo . '" height="' . $altura . '" border="0" />';
    echo '<img src="carne/barras/b.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';
    echo '<img src="carne/barras/p.gif" width="1" height="' . $altura . '" border="0" />';
}


$estadosbr = array("AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO");

function todosClientes($valor)
{
    include('conexao.php');
    $idempresa = $valor;
    $query = mysqli_query($conexao, "SELECT contato FROM cliente WHERE idempresa='$idempresa' AND contato <> ''") or die(mysqli_error($conexao));
    $rows = mysqli_num_rows($query);
    if ($rows >= 1) {
        return $rows;
    } else {
        return 0;
    }
}

function todosAtivo($valor)
{
    include('conexao.php');
    $idempresa = $valor;
    $query = mysqli_query($conexao, "SELECT contato FROM cliente WHERE idempresa='$idempresa' AND situacao='Ativo' AND contato <> ''") or die(mysqli_error($conexao));
    $rows = mysqli_num_rows($query);
    if ($rows >= 1) {
        return $rows;
    } else {
        return 0;
    }
}

function todosBloqueado()
{
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT contato FROM cliente WHERE idempresa='9999999999' AND situacao='Bloqueado' AND contato <> ''") or die(mysqli_error($conexao));
    $rows = mysqli_num_rows($query);
    if ($rows >= 1) {
        return $rows;
    } else {
        return 0;
    }
}

function todosCancelado()
{
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT contato FROM cliente WHERE idempresa='9999999999' AND situacao='Cancelado' AND contato <> ''") or die(mysqli_error($conexao));
    $rows = mysqli_num_rows($query);
    if ($rows >= 1) {
        return $rows;
    } else {
        return 0;
    }
}

function totalVencidos()
{
    include('conexao.php');
    $query = mysqli_query($conexao, "SELECT vencimento,valorpago,idcliente FROM cobranca WHERE valorpago=0.00 AND CURDATE() > vencimento GROUP BY idcliente")
        or die(mysqli_error($conexao));
    $rows = mysqli_num_rows($query);
    if ($rows >= 1) {
        return $rows;
    } else {
        return 0;
    }
}

//dias entre datas antes
function diasDatas($data_inicial, $data_final)
{
    $diferenca = strtotime($data_final) - strtotime($data_inicial);
    $dias = floor($diferenca / (60 * 60 * 24));
    return $dias;
}


//converser prar xml
function parseToXML($htmlStr)
{
    $xmlStr = str_replace('<', '&lt;', $htmlStr);
    $xmlStr = str_replace('>', '&gt;', $xmlStr);
    $xmlStr = str_replace('"', '&quot;', $xmlStr);
    $xmlStr = str_replace("'", '&#39;', $xmlStr);
    $xmlStr = str_replace("&", '&amp;', $xmlStr);
    return $xmlStr;
}

//planos
function planosServidor($idempresa)
{
    include('conexao.php');
    $sql = mysqli_query($conexao, "SELECT id,plano,nomeservidor FROM plano WHERE idempresa='$idempresa'");
    if (mysqli_num_rows($sql) >= 1) {
        while ($d = mysqli_fetch_array($sql)) {
            echo '<option value="' . $d['id'] . '">' . $d['nomeservidor'] . '-' . $d['plano'] . '</option>';
        }
    }
}


function gerarToken($entropy)
{
    $s = uniqid("", $entropy);
    $num = hexdec(str_replace(".", "", (string)$s));
    $index = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base = strlen($index);
    $out = '';
    for ($t = floor(log10($num) / log10($base)); $t >= 0; $t--) {
        $a = floor($num / pow($base, $t));
        $out = $out . substr($index, $a, 1);
        $num = $num - ($a * pow($base, $t));
    }
    return $out;
}

function situacao($valor)
{
    if ($valor == 'RECEBIDO') {
        return '<small class="label label-success"><i class="fa fa-clock-o"></i> ' . $valor . '</small>';
    } elseif ($valor == 'PENDENTE') {
        return '<small class="label label-primary"><i class="fa fa-clock-o"></i> ' . $valor . '</small>';
    } elseif ($valor == 'VENCIDO') {
        return '<small class="label label-danger"><i class="fa fa-clock-o"></i> ' . $valor . '</small>';
    } elseif ($valor == 'CANCELADO') {
        return '<small class="label label-dark"><i class="fa fa-clock-o"></i> ' . $valor . '</small>';
    } elseif ($valor == 'BAIXADO') {
        return '<small class="label label-info" title="baixado manual ou baixado pelo banco"><i class="fa fa-clock-o"></i> ' . $valor . '</small>';
    }
}

function situacaoBanco($valor)
{
    if ($valor == 1) {
        return $sitbanco = '1 - NORMAL';
    } elseif ($valor == 2) {
        return $sitbanco = '2 - MOVIMENTO CARTORIO';
    } elseif ($valor == 3) {
        return $sitbanco = '3 - EM CARTORIO';
    } elseif ($valor == 4) {
        return $sitbanco = '4 - TITULO COM OCORRENCIA DE CARTORIO';
    } elseif ($valor == 5) {
        return $sitbanco = '5 - PROTESTADO ELETRONICO';
    } elseif ($valor == 6) {
        return $sitbanco = '6 - LIQUIDADO';
    } elseif ($valor == 7) {
        return $sitbanco = '7 - BAIXADO';
    } elseif ($valor == 8) {
        return $sitbanco = '8 - TITULO COM PENDENCIA DE CARTORIO';
    } elseif ($valor == 9) {
        return $sitbanco = '9 - TITULO PROTESTADO MANUAL';
    } elseif ($valor == 10) {
        return $sitbanco = '10 - TITULO BAIXADO/PAGO EM CARTORIO';
    } elseif ($valor == 11) {
        return $sitbanco = '11 - TITULO LIQUIDADO/PROTESTADO';
    } elseif ($valor == 12) {
        return $sitbanco = '12 - TITULO LIQUID/PGCRTO';
    } elseif ($valor == 13) {
        return $sitbanco = '13 - TITULO PROTESTADO AGUARDANDO BAIXA';
    } elseif ($valor == 14) {
        return $sitbanco = '14 - TITULO EM LIQUIDACAO';
    } elseif ($valor == 15) {
        return $sitbanco = '15 - TITULO AGENDADO BB';
    } elseif ($valor == 16) {
        return $sitbanco = '16 - TITULO CREDITADO';
    } elseif ($valor == 17) {
        return $sitbanco = '17 - PAGO EM CHEQUE - AGUARD.LIQUIDACAO';
    } elseif ($valor == 18) {
        return $sitbanco = '18 - PAGO PARCIALMENTE';
    } elseif ($valor == 19) {
        return $sitbanco = '19 - PAGO PARCIALMENTE CREDITADO';
    } elseif ($valor == 21) {
        return $sitbanco = '21 - TITULO AGENDADO OUTROS BANCOS';
    } elseif ($valor == 80) {
        return $sitbanco = '80 - EM PROCESSAMENTO (ESTADO TRANSITÓRIO)';
    }
}
