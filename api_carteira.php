<?php 
//gerar cobranca
function gerarCobranca($id,$parcela,$vencimento,$valor,$obs,$idempresa){
        include('conexao.php');
        include('funcoes.php');

        @$obs = AspasBanco($obs);
        $query = mysqli_query($conexao,"SELECT * FROM cliente WHERE id='$id'");
        $dd = mysqli_fetch_array($query);
        
        @$idcobrancaprincipal = $id.$codigocobranca;

        for ($i = 1; $i <= $parcela; $i++) {
            if($i == 1 OR $parcela == 0){
                $codigocobranca = $id.date('Yms'); 
                @$vencimento = dataBanco($_POST['vencimento']);
            }else{
                $query0 = mysqli_query($conexao,"SELECT * FROM cobranca WHERE idcliente='$id' ORDER BY id DESC LIMIT 1");
                $reto = mysqli_fetch_array($query0);
                $vencimento = $reto['vencimento'];
                //@$vencimento = date($vencimento,strtotime(+1,'Month'));                
                $vencimento = date('Y-m-d', strtotime('+1 month', strtotime($vencimento)));
                $codigocobranca = ($id.$reto['id']) + 2;
            } 

            $nomecliente = $dd['nome'];
            $emailcliente = $dd['email'];
            $situacao = 'PENDENTE';
            mysqli_query($conexao,"INSERT INTO cobranca (banco,idempresa,idcliente,tipo,tipocobranca,idcobrancaprincipal,parcela,ncobranca,cliente,vencimento,valor,situacao,obs,datagerado) 
            VALUES ('Carteira','$idempresa','$id','CARTEIRA','Plano','$idcobrancaprincipal','$i','$codigocobranca','$nomecliente','$vencimento','$valor','PENDENTE','$obs',NOW())") 
            or die (mysqli_error($conexao));
        }
        $i = $i - 1;
        mysqli_query($conexao,"UPDATE cobranca SET nparcela='$i' WHERE idcobrancaprincipal='$idcobrancaprincipal'"); 

        echo persona('Gerado com sucesso');
}

//cancelar cobrança
function cancelarCobranca($id,$titulo){
    include('conexao.php');
    include('funcoes.php');
    mysqli_query($conexao,"UPDATE cobranca SET situacao='CANCELADO',obs='Realizado estorno',atualizado=NOW() WHERE id='$id'");
    mysqli_query($conexao,"UPDATE caixa SET tipo='CANCELADO' WHERE titulo='$titulo'") or die(mysqli_error($conexao));  

    echo persona('Estornado com sucesso');

}

?>             