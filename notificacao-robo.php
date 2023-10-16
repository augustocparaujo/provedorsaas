<?php
set_time_limit(0);
if(date('H:i') >= '08:00' AND date('H:i') <= '22:00'){ //disparo entre 8 da manha e 22

  include_once('conexao.php');
  include_once('funcoes.php');
  $query = mysqli_query($conexao, "SELECT * FROM notificacao_agendada WHERE datadisparo=CURDATE() AND situacao='' ORDER BY RAND() LIMIT 250") or die(mysqli_error($conexao));
    if (mysqli_num_rows($query) > 0) {
      while ($ret = mysqli_fetch_array($query)) {
        $contato = $ret['contato'];
        $msg = AspasBanco($ret['notificacao']);

        //verifica qual api ta sendo cadastrado
        $retApi = mysqli_query($conexao,"SELECT * FROM config_sms");
        if(mysqli_num_rows($retApi) > 0){
          $r = mysqli_fetch_array($retApi);
          if($r['api'] == 'smsnet'){

              include_once('api_smsnet.php');
              $status = enviaNotificacao($contato,$msg);
            if($status == true){
                mysqli_query($conexao,"UPDATE notificacao_agendada SET situacao=1,erro='' WHERE id='$ret[id]'") or die(mysqli_error($conexao));
              }else{
                mysqli_query($conexao,"UPDATE notificacao_agendada SET situacao='',erro='Não disparado' WHERE id='$ret[id]'") or die(mysqli_error($conexao));
              }

          }elseif($r['api'] == 'jhonata'){

              include_once('api_notificacao.php');
              enviaNotificacao($contato,$msg);
                mysqli_query($conexao,"UPDATE notificacao_agendada SET situacao=1,erro='' WHERE id='$ret[id]'") or die(mysqli_error($conexao));

          }

          }
        }

     

        sleep(1);
      
    }
 }