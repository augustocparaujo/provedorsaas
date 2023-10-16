<?php 
include('topo.php');
$id = $_GET['id'];
//chamado 
$query0 = mysqli_query($conexao,"SELECT chamado.*, contratos.rua,contratos.numero,contratos.bairro,contratos.latitude,contratos.longitude FROM chamado 
LEFT JOIN contratos ON chamado.idcontrato = contratos.id
WHERE chamado.id='$id'") or die (mysqli_error($conexao));
$retorno = mysqli_fetch_array($query0);
$nchamado = $retorno['nchamado'];
$idcliente = $retorno['idcliente'];

//cliente
$queryc = mysqli_query($conexao,"SELECT * FROM cliente WHERE id='$idcliente'") or die (mysqli_error($conexao));
$retornoc = mysqli_fetch_array($queryc);


$query = mysqli_query($conexao,"SELECT log_chamado.*, chamado.nomecliente,chamado.tipo,chamado.datacad AS dataabertura,chamado.obs AS logprincipal,chamado.dataatendimento 
FROM log_chamado LEFT JOIN chamado ON log_chamado.nchamado = chamado.nchamado
WHERE log_chamado.nchamado='$nchamado' ORDER BY log_chamado.datacad DESC,log_chamado.id DESC") or die (mysqli_error($conexao));

echo'
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="rolar">
    <!-- Main content -->
    <section class="content">
                  <div class="col-md-12">
                    <div class="box box-solid">
                    <div class="box-header with-border">
                    <h3 class="box-title">informações do chamado</h3>';
                    if(PermissaoCheck($idempresa,'clientes-alterar-chamado',$iduser)=='checked' OR $_SESSION['tipouser']=='Admin'){echo'
                    <a href="#" class="btn btn-primary" onclick="alterarChamado('.$id.')"><i class="fa fa-edit fa-2x"></i> Editar</a>';}echo'
                    </div>
                        <div class="box-body">
                          <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                              <tbody>
                                <tr style="background:#DCDCDC">
                                  <th>Protocolo</th>
                                  <th>Solicitante</th>
                                  <th>Abertura</th>
                                  <th>Descrição</th>
                                </tr>
                                <tr>
                                  <td>'.$retorno['nchamado'].'</td>
                                  <td>'.$retorno['usuariocad'].'</td>
                                  <td>'.dataForm($retorno['datacad']).'</td>
                                  <td>'.AspasForm($retorno['obs']).'</td>
                                </tr>
                              </tbody>
                            </table>
                        </div>

                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                              <tbody>
                                <tr style="background:#DCDCDC">
                                  <th>TECNICO RESPONSÁVEL</th>
                                  <th>CONTRATO DO CLIENTE</th>
                                  <th>NOME DO CLIENTE</th>
                                  <th>ENDEREÇO DO CONTRATO</th>
                                </tr>
                                <tr>
                                  <td>'.@$retorno['nometecnico'].'</td>
                                  <td>'.$retorno['idcontrato'].'</td>
                                  <td>'.$retornoc['nome'].'</td>
                                  <td>'.$retorno['rua'].', N° '.$retorno['numero'].', Bairro '.$retorno['bairro'].'</td>
                                </tr>
                              </tbody>
                            </table>
                        </div>

                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                              <tbody>
                                <tr style="background:#DCDCDC">
                                  <th>CONTATO CLIENTE</th>
                                  <th>CONTATO CLIENTE 2</th>
                                  <th>COMPLEMENTO</th>
                                  <th>OBSERVAÇÃO</th>
                                </tr>
                                <tr>
                                  <td>';
                                    if($retornoc['contato'] != ''){echo'
                                    <a href="tel:'.$retornoc['contato'].'" target="_blank"><i class="fa fa-phone text-black fa-2x"></i></a>&emsp;
                                    <a href="https://api.whatsapp.com/send?phone=55'.@$retornoc['contato'].'" target="_blank"><i class="fa fa-whatsapp text-green fa-2x"></i></a>';
                                    }echo'
                                  </td>
                                  <td>';
                                    if($retornoc['contato2'] != ''){echo'
                                    <a href="tel:'.$retornoc['contato2'].'" target="_blank"><i class="fa fa-phone text-black fa-2x"></i></a>&emsp;
                                    <a href="https://api.whatsapp.com/send?phone=55'.@$retornoc['contato2'].'" target="_blank"><i class="fa fa-whatsapp text-green fa-2x"></i></a>';
                                    }echo'
                                  </td>
                                  <td>'.@$retorno['complemento'].'</td>
                                  <td></td>
                                </tr>
                              </tbody>
                            </table>
                        </div>
                        
                      </div>
                    </div>
                  </div>';

                if($retorno['img1'] != '' OR $retorno['img2'] != '' OR $retorno['pdf'] != ''){echo'
                <hr><label for="inputDefault">Anexos</label><br>';
                    if($retorno['img1'] != ''){echo'
                    
                    <a href="#" data-fancybox data-type="iframe" data-src="docchamado/'.$retorno['img1'].'">
                    <img src="docchamado/'.$retorno['img1'].'" style="border-radius:5px; width:150px; margin:5px"/></a>&emsp;
                    ';}
                    if($retorno['img2'] != ''){echo'
                    <a href="#" data-fancybox data-type="iframe" data-src="docchamado/'.$retorno['img2'].'" style="border-radius:5px; width:150px; margin:5px">
                    <img src="docchamado/'.$retorno['img2'].'" style="border-radius:5px; width:150px; margin:5px"/>
                    </a>&emsp;
                    ';}
                    if($retorno['pdf'] != ''){echo'
                    <a href="#" data-fancybox data-type="iframe" data-src="docchamado/'.$retorno['pdf'].'"> <i class="fa fa-file-pdf-o text-red fa-2x"></i> Anexo</a>&emsp;
                
                    ';}
                }echo'<hr style="color:black">

      <!-- row -->
      <div class="row">
        <div class="col-md-12">
          <!-- The time line -->
          <ul class="timeline">';
          if(mysqli_num_rows($query) >= 1){
          while($ddl = mysqli_fetch_array($query)){
            if(@$datacad != $ddl['datacad']){ echo '<li class="time-label"><span class="bg-blue">'.dataForm($ddl['datacad']).'</span></li>'; }
              echo'
            <li>
              <div class="timeline-item">
                <h3 class="timeline-header">Cliente: <a><b>'.$ddl['nomecliente'].'</b></a> - Nº chamado: <b class="text-red">'.$ddl['nchamado'].'</b></h3>
                <div class="timeline-body">
                  Suporte: '.$ddl['usuariocad'].'<br />
                  Log: '.AspasForm($ddl['obs']).'';
                  
                     if($ddl['imgRetorno'] != '' OR $ddl['docRetorno'] != ''){
                    if($ddl['imgRetorno'] != ''){echo'                    
                    <a href="#" data-fancybox data-type="iframe" data-src="docchamado/'.$ddl['imgRetorno'].'">
                    <img src="docchamado/'.$ddl['imgRetorno'].'" style="border-radius:5px; width:150px; margin:5px"/></a>&emsp;
                    ';}
                    if($ddl['docRetorno'] != ''){echo'
                    <a href="#" data-fancybox data-type="iframe" data-src="docchamado/'.$ddl['docRetorno'].'"> <i class="fa fa-file-pdf-o text-red fa-2x"></i> Anexo</a>&emsp;
                
                    ';}
                }echo'
                      
                </div>
                <div class="timeline-footer"><hr>
                  <p>
                    <h4><b>Descrição do chamado</b></h4>
                  Suporte: <b>'.$ddl['usuariocad'].'</b><br /> 
                  Tipo de chamado: <b>'.$ddl['tipo'].'</b><br />
                  Data abertura: <b>'.dataForm($ddl['dataabertura']).'</b><br />
                  Data para atendimento: <b>'.dataForm($ddl['dataatendimento']).'</b><br />
                  <input type="text" class="hidden" id="dataatendimento" value="'.$ddl['dataatendimento'].'"/>
                  Descrição: <b>'.AspasForm($ddl['logprincipal']).'</b><br />';
                  if($ddl['situacao'] == 'ABERTO'){ echo '<a class="btn btn-info btn-flat">'.$ddl['situacao'].'</a>'; }
                  if($ddl['situacao'] == 'SOLUCIONADO'){ echo '<a class="btn btn-success btn-flat">'.$ddl['situacao'].'</a>'; }
                  if($ddl['situacao'] == 'PENDENTE' OR $ddl['situacao'] == 'PENDENTE TERCEIRO'){ echo '<a class="btn btn-warning btn-flat">'.$ddl['situacao'].'</a>'; }
                  if($ddl['situacao'] == 'CANCELADO'){ echo '<a class="btn btn-danger btn-flat">'.$ddl['situacao'].'</a>'; }
                  if($ddl['situacao'] == 'REABERTO'){ echo '<a class="btn btn-default btn-flat">'.$ddl['situacao'].'</a>'; }
                  echo'
                  </p>
                </div>
              </div>
            </li>
            <!-- fim de uma data -->';
            $datacad = $ddl['datacad'];
          }
        }
            echo'
          </ul>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- modal add log-->
<div class="modal" id="addLog" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar comentário</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAddLog" autocomplete="off" enctype="multipart/form-data">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
                    <input type="text" class="hidden" name="id" value="'.@$id.'"/>
                    <input type="text" class="hidden" name="nchamado" value="'.@$nchamado.'"/>
                    <input type="text" class="hidden" name="dataatendimento0" id="colocardata"/>
                    <label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrição
                        <textarea rows="3" class="form-control" placeholder="Descreva o atendimento ou comentário" name="obs" required></textarea>
                    </label>
                    
                      
                          <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Foto (JPG OU JPEG)
                          <input type="file" class="form-control" name="imgRetorno" placeholder="Foto" id="inputDefault" accept="image/jpg, image/jpeg"/>
                      </label>

                     
                          <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">PDF
                          <input type="file" class="form-control" name="docRetorno" placeholder="Foto" id="inputDefault" accept="application/pdf"/>
                      </label>
            
                    <div class="row"></div>
                    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Nova data para atendimento
                      <input type="date" class="form-control" name="dataatendimento"/>
                      <small class="text-red">*seu houver mudança</small>
                    </label>
                    <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Situação
                    <select type="text" class="form-control" name="situacao" required>
                        <option value="">SELECIONE</option>
                        <option value="ABERTO">ABERTO</option>
                        <option value="CANCELADO">CANCELADO</option>
                        <option value="PENDENTE">PENDENTE</option>
                        <option value="PENDENTE TERCEIRO">PENDENTE TERCEIRO</option>
                        <option vaue="REABERTO">REABERTO</option>
                        <option value="SOLUCIONADO">SOLUCIONADO</option>
                    </select>
                    </label>
                    <div class="row"></div><hr>
                    <ol style="font-size:12px">
                        <li>INCIDENTE: Problema em equipamento, rompimento de cabo, etc</li>
                        <li>INSTALAÇÃO: Agendamento de instalação de equipamento, assinatura, etc</li>
                        <li>SOLICITAÇÃO: Troca de senha wifi, mudana de vencimento, mudança de plano, etc</li>
                        <li>PENDENTE TERCEIRO: falta de energia, etc</li>
                        <li class="text-red"> SITUAÇÃO REABERTO É PARA QUANDO O CHAMADO FOR SOLUCIONADO OU CANCELADO E PRECISAR SE REABERTO POR ALGUM MOTIVO</li>
                    </ol>
	        	</div>
        	</div>       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- modal add log-->
  
<a href="#" class="button-canto-inferior" title="adicionar log" data-toggle="modal" data-target="#addLog"><i style="margin-top:16px" class="fa fa-plus"></i></a>';
echo'
<!-- modal abrir chamado-->
<div class="modal" id="AlterarChamado" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAlterarChamado" autocomplete="off">
      <div class="modal-body">
        	<div class="row" id="retornoAlterar">        	
        	</div>       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- modal cliente cadastrar-->';

include('rodape.php'); 
?>
<script>
  $('#chamados').addClass('active');
  $('#formAddLog').submit(function(){
    var data0 = $('#dataatendimento').val();
    $('#colocardata').val(data0);
    $('#addLog').modal('hide');
    $('#processando').modal('show');
    var formData = new FormData(this);
    $.ajax({
      type:'post',
      url:'insert-log-chamado.php',
      data:formData,
      success:function(data){
        $('#formAddLog').each(function(){this.reset();});
        $('#processando').modal('hide');
        $('#retorno').show().html(data);
        history.go();
      },
       cache: false,
          contentType: false,
          processData: false,
    });
    return false;
  });
  //alterarChamado
  function alterarChamado(id){
    $('#AlterarChamado').modal('show');
       $.get('clientes-chamado-retorno-alterar.php',{id:id},function(data){
        $('#retornoAlterar').show().html(data);
       })
      return false;
  }
    //salvar chamado
    $('#formAlterarChamado').submit(function(){
      $('#AlterarChamado').modal('hide');
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'update-chamado.php',
        data:$('#formAlterarChamado').serialize(),
        success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(2500).html(data);
          window.setTimeout(function() { history.go(); }, 2501);
        }
      });
      return false;
    });  

</script>