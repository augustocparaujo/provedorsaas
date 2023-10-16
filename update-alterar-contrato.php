<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
require_once('routeros_api.class.php');
$mk = new RouterosAPI();

$idempresa = $_SESSION['idempresa'];
$logomarcauser = $_SESSION['logomarcauser'];
$iduser = $_SESSION['iduser'];
$nomeuser = $_SESSION['usuario'];//pega usuario que est� executando a a��o
$situacaouser = $_SESSION['situacaouser'];
$ip = $_SERVER['REMOTE_ADDR']; // pegar ip da maquina
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); //pega nome da maquina
if(isset($_SESSION['iduser'])!=true){echo '<script>location.href="sair.php";</script>'; }

$id = $_POST['id'];
@$idcliente = @$_POST['idcliente'];
$cep = limpaCPF_CNPJ($_POST['cep']);
$rua = AspasBanco($_POST['rua']);
$numero = $_POST['numero'];
$bairro = AspasBanco($_POST['bairro']);
$municipio = AspasBanco($_POST['municipio']);
$estado = $_POST['estado'];
$complemento = AspasBanco($_POST['complemento']);
$ibge = $_POST['ibge'];
$situacao = $_POST['situacao'];
$ativacao = dataBanco($_POST['ativacao']);
$latitude = AspasBanco($_POST['latitude']);
$longitude = AspasBanco($_POST['longitude']);
@$nsecomodato = AspasBanco($_POST['nsecomodato']);
@$modelocomodato = AspasBanco($_POST['modelocomodato']);
@$maccomodato = AspasBanco($_POST['maccomodato']);
            
$plano = $_POST['plano'];
$queryplano = mysqli_query($conexao,"SELECT plano.plano,servidor,servidor.id,ip,user,password FROM plano LEFT JOIN servidor ON plano.servidor = servidor.id WHERE plano.id='$plano'");
$retorno = mysqli_fetch_array($queryplano);
$nomeplano = $retorno['plano'];
$idservidor = $retorno['servidor'];
$ipservidor = $retorno['ip'];
$user = $retorno['user'];
$passwords = $retorno['password'];

$login = AspasBanco($_POST['login']);
$password = $_POST['password'];
$ip = $_POST['ip'];
$service = $_POST['service'];
$porta = $_POST['porta'];
$mac = $_POST['mac'];
//situação
if($nomeplano == 'Bloqueado'){ $nomeplano = 'Bloqueado'; }
if(!empty($id)){   

                  $query0 = mysqli_query($conexao,"SELECT * FROM contratos WHERE id='$id'") or die (mysqli_error($conexao));
                  $rest = mysqli_fetch_array($query0); 

                  if($rest['login'] != $login OR $rest['senha'] != $password OR $rest['service'] != $service OR $rest['ip'] != $ip OR $rest['mac'] != $mac OR $nomeplano != $rest['nomeplano']){

                    if(mysqli_num_rows($query0) >= 1){
                      $loginantigo = utf8_decode($rest['login']);

                      //teste de remoção antecipada (teste para solução de delay)
                      if($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
                        $mk->comm("/ppp/secret/remove", array("?name" =>  utf8_decode($loginantigo),));  
                        $find = $mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($loginantigo),));
                        if (count($find) >= 1) {
                          $Finduser  = $find[0];
                          $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                        }
                      }//fim

                      if($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
                        $find = @$mk->comm("/ppp/secret/print", array("?name" =>  utf8_decode(AspasBanco($loginantigo)),));     
                        //$find = $mk->comm("/ppp/secret/remove", array(".id" =>  $Finduser['.id'],));  
                        //existe
                        if (count($find) >= 1) {
                          if($situacao != 'Cancelado'){  
                            $Finduser  = $find[0];                     
                            //login
                            if($login != $rest['login']){
                              $find = $mk->comm("/ppp/secret/set", array(
                                ".id" =>  $Finduser['.id'],
                                "name" => utf8_decode(AspasBanco($login)),
                              ));
                              $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($loginantigo),));
                              if (count($find) >= 1) {
                                $Finduser  = $find[0];
                                $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                                echo $Finduser['.id'];
                              }
                            }                    
                            //senha
                            if($password != $rest['senha']){
                              $find = $mk->comm("/ppp/secret/set", array(
                                ".id" =>  $Finduser['.id'],
                                "password" => utf8_decode(AspasBanco($password)),
                              ));
                              $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($loginantigo),));
                              if (count($find) >= 1) {
                                $Finduser  = $find[0];
                                $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                              }
                            }
                            //plano
                            if($nomeplano != $rest['nomeplano']){
                              if($situacao == 'Bloqueado'){ $situacao = 'Bloqueado'; }
                              $find = $mk->comm("/ppp/secret/set", array(
                                ".id" =>  $Finduser['.id'],
                                "profile" =>  utf8_decode(AspasBanco($nomeplano)),
                              ));
                              $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($loginantigo),));
                              if (count($find) >= 1) {
                                $Finduser  = $find[0];
                                $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                              }
                            }                    
                            //service
                            if($service != $rest['service']){
                              $find = $mk->comm("/ppp/secret/set", array(
                                ".id" =>  $Finduser['.id'],
                                "service" => utf8_decode($service),
                              ));
                              $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($loginantigo),));
                              if (count($find) >= 1) {
                                $Finduser  = $find[0];
                                $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                              }
                            } 
                            //ip
                            if($ip != $rest['ip']){
                              $find = $mk->comm("/ppp/secret/set", array(
                                ".id" =>  $Finduser['.id'],
                                "remote-address" => utf8_decode(AspasBanco($ip)),
                              ));
                              $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($loginantigo),));
                              if (count($find) >= 1) {
                                $Finduser  = $find[0];
                                $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                              }
                            }
                            //mac
                            if($mac != $rest['mac']){
                              $find = $mk->comm("/ppp/secret/set", array(
                                ".id" =>  $Finduser['.id'],
                                "caller-id" => utf8_decode(AspasBanco($mac)),
                              ));
                              $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($loginantigo),));
                              if (count($find) >= 1) {
                                $Finduser  = $find[0];
                                $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                              }
                            }
                            //se cancelado remove
                          }else{     
                            if($mk->connect($ipservidor, decrypt($user), decrypt($passwords))) {
                              $find = @$mk->comm("/ppp/secret/print", array("?name" =>  utf8_decode($loginantigo),));                                        
                              if (count($find) >= 1) {  
                                $Finduser  = $find[0];
                                $find = $mk->comm("/ppp/secret/remove", array(".id" =>  $Finduser['.id'],));                                                        
                                $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));
                                if (count($find) >= 1) {
                                  $Finduser  = $find[0];
                                  $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                                }
                              }
                            }
                          }//ende se existir
                        }else{//se não existir cria                                           
                          $mk->connect($ipservidor, decrypt($user), decrypt($passwords));
                          @$mk->comm("/ppp/secret/add", array(
                            "name" => utf8_decode($login),
                            "password" => utf8_decode($password),
                            "profile" => utf8_decode(AspasBanco($nomeplano)),
                            "service" => utf8_decode($service),
                          ));

                          //se vier ip e mac adiciona depois
                          $find = @$mk->comm("/ppp/secret/print", array("?name" =>  utf8_decode($login),));                                        
                          //existe
                          if (count($find) >= 1) { 
                            $Finduser  = $find[0];                   
                            //ip
                            if($ip != ''){
                              $find = $mk->comm("/ppp/secret/set", array(
                                ".id" =>  $Finduser['.id'],
                                "remote-address" => utf8_decode(AspasBanco($ip)),
                              ));
                              $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));
                              if (count($find) >= 1) {
                                $Finduser  = $find[0];
                                $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                              }
                            }
                            //mac
                            if($mac != ''){
                              $find = $mk->comm("/ppp/secret/set", array(
                                ".id" =>  $Finduser['.id'],
                                "caller-id" => utf8_decode(AspasBanco($mac)),
                              ));
                              $find = @$mk->comm("/ppp/active/print", array("?name" =>  utf8_decode($login),));
                              if (count($find) >= 1) {
                                $Finduser  = $find[0];
                                $find = $mk->comm("/ppp/active/remove", array(".id" =>  $Finduser['.id'],));
                              }
                            }
                          }

                        } 
                      }//conecta no servidor
                    }
                  }

        mysqli_query($conexao,"UPDATE contratos SET cep='$cep',rua='$rua',numero='$numero',bairro='$bairro',municipio='$municipio',estado='$estado',complemento='$complemento',ibge='$ibge',
        login='$login',senha='$password',ip='$ip',service='$service',porta='$porta',mac='$mac',plano='$plano',nomeplano='$nomeplano',ativacao='$ativacao',
        situacao='$situacao',atualizado=NOW(),latitude='$latitude',longitude='$longitude',nsecomodato='$nsecomodato',modelocomodato='$modelocomodato',maccomodato='$maccomodato' WHERE id='$id'") or die (mysqli_error($conexao)); 
        
        echo update();       
}else{
  
  mysqli_query($conexao,"INSERT INTO contratos (idcliente,idempresa,plano,nomeplano) VALUES('$idcliente','$idempresa','$plano','$nomeplano')") or die (mysqli_error($conexao));  
  echo insert();
  
}
?>