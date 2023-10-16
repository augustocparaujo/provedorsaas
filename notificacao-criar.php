<?php
include('conexao.php');
include('funcoes.php');

$query = mysqli_query($conexao, "SELECT * FROM config_sms WHERE antes > 0 AND depois > 0 AND texto <> ''") or die(mysqli_error($conexao));
if (mysqli_num_rows($query) > 0) {
  $d = mysqli_fetch_array($query);
  $antes = $d['antes'];
  $depois = $d['depois'];
  $texto = $d['texto'];
  $hoje = date('Y-m-d');
  //seleciona ate um mês atrasado e 20 dias pra frente da data de hoje
  $umMesAtrasado = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-01'))));
  $vinteDiasPraFrente = date('Y-m-d', strtotime('+10 days'));

  $query = mysqli_query($conexao, "SELECT cobranca.*, cliente.nome,contato FROM cobranca
    INNER JOIN cliente ON cobranca.idcliente = cliente.id
    WHERE cobranca.vencimento BETWEEN '$umMesAtrasado' AND '$vinteDiasPraFrente' AND cobranca.situacao IN ('PENDENTE','VENCIDO') AND cliente.contato <> ''") or die(mysqli_error($conexao));
  if (mysqli_num_rows($query) > 0) {
    while ($ret = mysqli_fetch_array($query)) {
      $dataantes = date('Y-m-d', strtotime('-' . $antes . ' days', strtotime($ret['vencimento'])));
      $datadepois = date('Y-m-d', strtotime('+' . $depois . ' days', strtotime($ret['vencimento'])));
      //montar a notificação
      $notificacao = str_replace('{{nomecliente}}', $ret['nome'], $texto);
      $notificacao = str_replace('{{descricao}}', AspasBanco($ret['obs']), $notificacao);
      $notificacao = str_replace('{{valor}}', Real($ret['valor']), $notificacao);
      $notificacao = str_replace('{{vencimento}}', dataForm($ret['vencimento']), $notificacao);
      if (!empty($ret['link']) and strpos($notificacao, '{{link}}') !== false) {

        $notificacao = str_replace('{{link}}', $ret['link'], $notificacao);
      } else {
        $notificacao = str_replace('{{link}}', '', $notificacao);
      }

      if (strpos($notificacao, '{{mercadopago}}') !== false) {
        $notificacao = str_replace('{{mercadopago}}', 'Para pagamento via pix clique no link abaixo: https://painel.mkgestor.com.br/cascata.php?id' . $ret['id'], $notificacao);
      }

      //criar notificação antes
      $sqlnotifica = mysqli_query($conexao, "SELECT * FROM notificacao_agendada WHERE idcobranca='$ret[id]' AND contato='$ret[contato]' AND datadisparo='$dataantes'");
      if (mysqli_num_rows($sqlnotifica) == 0) {
        mysqli_query($conexao, "INSERT INTO notificacao_agendada (idcobranca,nome,contato,notificacao,datadisparo) 
            VALUES('$ret[id]','$ret[nome]','$ret[contato]','$notificacao','$dataantes')");
        die();
      }
      //criar notificação depois
      $sqlnotifica2 = mysqli_query($conexao, "SELECT * FROM notificacao_agendada WHERE idcobranca='$ret[id]' AND contato='$ret[contato]' AND datadisparo='$datadepois'");
      if (mysqli_num_rows($sqlnotifica2) == 0) {
        mysqli_query($conexao, "INSERT INTO notificacao_agendada (idcobranca,nome,contato,notificacao,datadisparo) 
            VALUES('$ret[id]','$ret[nome]','$ret[contato]','$notificacao','$datadepois')");
        die();
      }
    }
    die();
  }
}
