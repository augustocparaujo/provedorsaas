<?php 
include('topo.php');

if(@$_POST['inicio'] != ''){ @$inicio = $_POST['inicio']; }else{ @$inicio = date('Y-m-01');}
if(@$_POST['fim'] != ''){ @$fim = $_POST['fim']; }else{ @$fim = date('Y-m-t');}
if(@$_POST['tipo'] != ''){ $tipo = $_POST['tipo']; }else{ $tipo = '';}
$hoje = date('Y-m-d');

$sql = mysqli_query($conexao, "SELECT * FROM j_gastos 
WHERE idempresa='$idempresa' AND vencimento BETWEEN '$inicio' AND '$fim' AND tipo LIKE '$tipo%' ORDER BY categoria ASC")or die(mysqli_error($conexao));

//fixo
$sqle = mysqli_query($conexao, "SELECT SUM(valor) AS totalfixo FROM j_gastos WHERE 
idempresa='$idempresa' AND tipo='Fixo' AND vencimento BETWEEN '$inicio' AND '$fim'") or die(mysqli_error($conexao));
$rete = mysqli_fetch_array($sqle);

//não fixo
$sqle2 = mysqli_query($conexao, "SELECT SUM(valor) AS totalnaofixo FROM j_gastos WHERE 
idempresa='$idempresa' AND tipo='Não fixo' AND vencimento BETWEEN '$inicio' AND '$fim'") or die(mysqli_error($conexao));
$rete2 = mysqli_fetch_array($sqle2);



echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  

    <div class="row">
    <form method="post">
        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">Data ínicio
            <input type="date" name="inicio" class="form-control" value="'.date(@$inicio).'" required/>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">Data fim
            <input type="date" name="fim" class="form-control" value="'.date(@$fim).'" required/>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">Tipo
            <select type="text" class="form-control" name="tipo">
                <option value="">selecone</option>
                <option value="Fixo">Fixo</option>
                <option value="Não fixo">Não fixo</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"><br>
        <button type="submit" class="btn btn-default btn-block"><i class="fa fa-search"></i> Buscar</button>
        </div>
    </form>';
    if(PermissaoCheck($idempresa,'cadastrar-gasto',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
      
      <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"><br>
      <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#cadastrarGasto"><i class="fa fa-plus"></i> Cadastrar</a></div>';
      }echo'
</div>
<br>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="col-lg-6 col-md-6 col-sm-6" style="background: white">
        <div style="background-color: green; color: white; text-align:center; font-weight:bold; font-size: 20px;">
            Total fixo
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center; align-ifont-weight:bold; font-size: 20px">
            <p class="text-blue">R$ '.Real(@$rete['totalfixo']).' </p>
        </div>

    </div>

    <div class="col-lg-6 col-md-6 col-sm-6" style="background: white">
    <div class="col-lg-12 col-md-12 col-sm-12" style="background-color: blue; color: white; text-align:center; font-weight:bold; font-size: 20px">
        Total não fixo
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12" style="text-align:center; align-ifont-weight:bold; font-size: 20px">
        <p class="text-blue">R$ '.Real(@$rete2['totalnaofixo']).' </p>
    </div>
    </div>
  </div>
</div>
<br>

      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Gastos mensais</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive no-padding">
              <table class="table table-hover">                
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody>';

                    if(mysqli_num_rows($sql) >= 1){
                      while ($dd = mysqli_fetch_array($sql)) {
                              echo'<tr>
                                  <td>'.$dd['categoria'].'</td>
                                  <td>'.AspasForm($dd['descricao']).'</td>
                                  <td>'.AspasForm($dd['tipo']).'</td>
                                  <td>'.dataForm($dd['vencimento']).'</td>
                                  <td>'.Real($dd['valor']).'</td>
                                  <td>';
                                  if(PermissaoCheck($idempresa,'alterar-gasto',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                                      <a onclick="alterarGasto('.$dd['id'].')" title="alterar item"><i class="fa fa-edit fa-2x"></i></a>';
                                  }
                                  if(PermissaoCheck($idempresa,'excluir-gasto',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                                      <a onclick="excluirGasto('.$dd['id'].')" title="excluir item"><i class="fa fa-trash fa-2x text-danger"></i></a>';
                                  }echo'
                                  </td>
                              </tr>';                      
                      }}else{ echo'<tr><td colspan="8">sem registro</td></tr>';}

                    echo'</tbody>             
              </table>
              </div>                                                         
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      </form>        
     
      <!--/.row-->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- modal item-->
<div class="modal" id="cadastrarGasto" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCadastro" autocomplete="off">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
              <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Categoria
              <select type="text" class="form-control" name="categoria">
                    <option value="">selecione</option>';
                    $queryp = mysqli_query($conexao,"SELECT categoria FROM j_gastos GROUP BY categoria ORDER BY categoria ASC") or die (mysqli_error($conexao));
                    if(mysqli_num_rows($queryp) >= 1){
                        while($retp = mysqli_fetch_array($queryp)){
                            echo'<option value="'.$retp['categoria'].'">'.$retp['categoria'].'</option>';
                        }
                    }
                echo'
              </select>
              </label>
              <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Nova categoria
                <input type="text" class="form-control" placeholder="Nova categoria" name="novacategoria"/>
              </label>
              <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Tipo
              <select type="text" class="form-control" name="tipo">
                    <option value="">selecione</option>
                    <option value="Fixo">Fixo</option>
                    <option value="Não fixo">Não fixo</option>
              </select>
              </label>
              <label class="col-xs-12 col-lg-6 col-md-12 col-sm-12">Vencimento
                  <input type="date" class="form-control" name="vencimento"/>
              </label>
              <label class="col-xs-12 col-lg-6 col-md-12 col-sm-12">Valor
                  <input type="text" class="form-control real" placeholder="Valor" name="valor"/>
              </label>
              <label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrição
                <input type="text" class="form-control" placeholder="Descrição" name="descricao"/>
              </label>
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
<!-- modal item-->';

include('rodape.php');
?>
<script>
    $('.financeiro').addClass('active menu-open');
    $('#gastos-mensais').addClass('active');
     //cadastrar gasto
     $('#formCadastro').submit(function(){
      $('#cadastrarGasto').modal('hide');
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'insert-gasto.php',
        data:$('#formCadastro').serialize(),
        success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(2500).html(data);
          window.setTimeout(function() { history.go(); }, 2501);
        }
      });
      return false;
    });
      //excluir gato
      function excluirGasto(id){
      var r = confirm("Excluir item?");
      $('#processando').modal('show');
      if (r == true) {
        $.get('excluir-gasto.php',{id:id},function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(2500).html(data);
          window.setTimeout(function() { history.go(); }, 2501);
        })
      }
      return false;
    }
</script>