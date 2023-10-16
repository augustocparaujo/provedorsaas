<?php 
//gerar cobranca
function gerarCobranca($id,$parcela,$vencimento,$valor,$obs){
        include('conexao.php');
        include('funcoes.php');

        @$obs = AspasBanco($obs);
        $query = mysqli_query($conexao,"SELECT * FROM user WHERE id='$id'");
        $dd = mysqli_fetch_array($query);
        
        for ($i = 1; $i <= $parcela; $i++) {
            if($i == 1 OR $parcela == 0){
                @$vencimento = dataBanco($_POST['vencimento']);
            }else{
                $query0 = mysqli_query($conexao,"SELECT * FROM user_cobranca WHERE idcliente='$id' ORDER BY id DESC LIMIT 1");
                $reto = mysqli_fetch_array($query0);
                $vencimento = $reto['vencimento'];
                //@$vencimento = date($vencimento,strtotime(+1,'Month'));                
                $vencimento = date('Y-m-d', strtotime('+1 month', strtotime($vencimento)));
            } 

            //idcliente,code,link,codigobarra,ncobranca,vencimento,diabloqueio,valor,datapagamento,situacao,data
            mysqli_query($conexao,"INSERT INTO user_cobranca (idcliente,vencimento,valor,situacao,data,obs) 
            VALUES ('$id','$vencimento','$valor','PENDENTE',NOW(),'$obs')") 
            or die (mysqli_error($conexao));
        }
        $i = $i - 1;

        echo persona('Gerado com sucesso');
}

//cancelar cobrança
function cancelarCobranca($id){
    include('conexao.php');
    include('funcoes.php');
    mysqli_query($conexao,"DELETE FROM user_cobranca WHERE id='$id'");
    echo persona('Cencelado com sucesso');
}             