<?php 
include('topo.php');
echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <div class="col-xs-12">
              <h3 class="box-title">Controle de estoque</h3>
              </div>
              <div class="row">
                <div class="box-tools">
                  <div class="col-xs-12 col-md-4 col-sm-4 col-lg-2">
                    <button type="button" class="btn btn-default btn-block" onclick="history.back();"><i class="fa  fa-chevron-left"></i> Voltar</button>
                  </div>';
                  if(PermissaoCheck($idempresa,'alterar-categoria',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                    <div class="col-xs-12 col-md-4 col-sm-4 col-lg-2">
                      <button type="button" class="btn bg-olive btn-block" data-toggle="modal" data-target="#Categorias"><i class="fa fa-tags"></i> Categorias</button>
                    </div>';
                  }
                  if(PermissaoCheck($idempresa,'alterar-fornecedor',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                    <div class="col-xs-12 col-md-4 col-sm-4 col-lg-2">
                      <button type="button" class="btn bg-purple btn-block" data-toggle="modal" data-target="#Fornecedores"><i class="fa fa-shopping-cart"></i> Fornecedores</button>
                    </div>';
                    }
                    if(PermissaoCheck($idempresa,'cadastrar-item',$iduser)=='checked' OR $_SESSION['tipouser'] == 'Admin' ){ echo'
                      <div class="col-xs-12 col-md-4 col-sm-4 col-lg-2">
                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#CadastrarItem"><i class="fa fa-plus"></i> Cadastrar</button>
                      </div>';
                  }echo'
                </div>
              </div>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive no-padding">
                <table class="table table-hover">                
                  <thead>
                      <tr>
                          <th>#</th>
                          <th>Categoria</th>
                          <th>Fornecedor</th>
                          <th>Descrição</th>
                          <th>QUANT</th>
                          <th>Usuário</th>
                          <th>Data</th>
                          <th>#</th>
                      </tr>
                      </thead>
                      <tbody id="tabela"></tbody>             
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
<div class="modal" id="CadastrarItem" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cadastrar Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formCadastroItem" autocomplete="off">
      <div class="modal-body">
        	<div class="row">
        		<div class="col-lg-12">
              <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Categoria
              <select type="text" class="form-control" name="categoria">
                    <option value="">selecione</option>';
                    $query = mysqli_query($conexao,"SELECT * FROM j_categoria_estoque ORDER BY nome_cat ASC") or die (mysqli_error($conexao));
                    if(mysqli_num_rows($query) >= 1){
                        while($ret = mysqli_fetch_array($query)){
                            echo'<option value="'.$ret['id'].'">'.$ret['nome_cat'].'</option>';
                        }
                    }
                echo'
              </select>
              </label>
              <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Nova categoria
                <input type="text" class="form-control" placeholder="Nova categoria" name="novacategoria"/>
              </label>

              <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Forncedor
              <select type="text" class="form-control" name="fornecedor">
                    <option value="">selecione</option>';
                    $query = mysqli_query($conexao,"SELECT * FROM j_fornecedor_equip ORDER BY descricao ASC") or die (mysqli_error($conexao));
                    if(mysqli_num_rows($query) >= 1){
                        while($ret = mysqli_fetch_array($query)){
                            echo'<option value="'.$ret['id'].'">'.$ret['descricao'].'</option>';
                        }
                    }
                echo'
              </select>
              </label>
              <label class="col-xs-12 col-lg-6 col-md-6 col-sm-12">Novo fornecedor
                <input type="text" class="form-control" placeholder="Novo fornecedor" name="novofornecedor"/>
              </label>

              <label class="col-xs-12 col-lg-12 col-md-12 col-sm-12">Descrição
                  <input type="text" class="form-control" placeholder="Descrição" name="descricao"/>
              </label>

              <label class="col-xs-12 col-lg-6 col-md-12 col-sm-12">Quantidade
                  <input type="number" class="form-control" placeholder="Quantidade" name="quantidade"/>
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
<!-- modal item-->

<!-- modal alterar item-->
<div class="modal" id="AlterarItem" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAlterarItem">
      <div class="modal-body" id="retornoItem"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- modal alterar item-->

<!-- modal saida item-->
<div class="modal" id="SaidaItem" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Saída Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formSaidaItem">
      <div class="modal-body" id="retornoSaidaItem"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Salvar</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- modal saida item-->

<!-- modal categorias-->
<div class="modal" id="Categorias" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Categorias</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">                
            <thead>
                <tr style="background-color:gray">
                    <th>Nome</th>
                    <th>#</th>
                </tr>
                </thead>
                <tbody>';
                $queryc = mysqli_query($conexao,"SELECT * FROM j_categoria_estoque ORDER BY nome_cat ASC");
                while($ret = mysqli_fetch_array($queryc)){
                  echo'
                    <tr>
                      <td>'.AspasForm($ret['nome_cat']).'</td>
                      <td><a onclick="alterarCategoria('.$ret['id'].')" class="btn btn-social-icon btn-edit" title="alterar categoria"><i class="fa fa-edit"></i></a></td>
                    </tr>
                  ';
                }
                echo'
                </tbody>             
          </table>
        </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<!-- modal categorias-->

<!-- modal alterar categoria-->
<div class="modal" id="alterarCategoria" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar categoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form id="formAltCategoria">
          <div class="modal-body" id="retornoCategoria"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
        </form>
    </div>
  </div>
</div>
<!-- modal alterar categoria -->

<!-- modal fornecedor-->
<div class="modal" id="Fornecedores" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Fornecedores</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-hover">                
            <thead>
              <tr style="background-color:gray">
                  <th>Nome</th>
                  <th>#</th>
              </tr>
            </thead>
            <tbody>';
              $queryf = mysqli_query($conexao,"SELECT * FROM j_fornecedor_equip ORDER BY descricao ASC");
              while($retf = mysqli_fetch_array($queryf)){
                echo'
                  <tr>
                    <td>'.AspasForm($retf['descricao']).'</td>
                    <td><a onclick="alterarFornecedor('.$retf['id'].')" class="btn btn-social-icon btn-edit" title="alterar fornecedor"><i class="fa fa-edit"></i></a></td>
                  </tr>';
              }echo'
            </tbody>             
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<!-- modal fornecedor-->

<!-- modal alterar Fornecedor-->
<div class="modal" id="alterarFornecedor" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alterar Fornecedor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form id="formAltFornecedor">
          <div class="modal-body" id="retornoFornecedor"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
        </form>
    </div>
  </div>
</div>
<!-- modal alterar Fornecedor -->';

include('rodape.php');
?>
<script>
     $('.parametros').addClass('active menu-open');
    $('#controle-estoque').addClass('active');
    //tabela
    $(document).ready(function() { tabela(); });
    function tabela(){
      $.ajax({
        type:'post',
        url:'tab-estoque.php',
        data:'html',
        success:function(data){
          $('#tabela').show().html(data);
        }
      });
      return false;
    }
    //cadastrar item
      $('#formCadastroItem').submit(function(){
      $('#CadastrarItem').modal('hide');
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'insert-estoque.php',
        data:$('#formCadastroItem').serialize(),
        success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(2500).html(data);
          tabela();
        }
      });
      return false;
    });
    //alterar item
    function alterarItem(id){
      $('#AlterarItem').modal('show');
      $.get('retorno-item-estoque.php',{id:id},function(data){
        $('#retornoItem').show().html(data);
      });
      return false;
    }
      //alterar item
      $('#formAlterarItem').submit(function(){
      $('#AlterarItem').modal('hide');
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'insert-estoque.php',
        data:$('#formAlterarItem').serialize(),
        success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(2500).html(data);
          tabela();
        }
      });
      return false;
    });
        //saida item
        function saidaItem(id){
      $('#SaidaItem').modal('show');
      $.get('retorno-para-saida-estoque.php',{id:id},function(data){
        $('#retornoSaidaItem').show().html(data);
      });
      return false;
    }
    //saidan item
    $('#formSaidaItem').submit(function(){
      $('#SaidaItem').modal('hide');
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'saida-estoque.php',
        data:$('#formSaidaItem').serialize(),
        success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(2500).html(data);
          tabela();
        }
      });
      return false;
    });
    //excluir item
    function excluirItem(id){
      var r = confirm("Excluir item?");
      $('#processando').modal('show');
      if (r == true) {
        $.get('excluir-estoque-item.php',{id:id},function(data){
          $('#processando').modal('hide');
          $('#retorno').show().html(data);
          tabela();
        })
      }
      return false;
    }
    //exibir item
    function exibirItem(id){
      window.open('controle-saida-item-estoque.php?id='+id);
    }
    //alterar categoria
    function alterarCategoria(id){
      $('#alterarCategoria').modal('show');
      $.get('retorno-categoria.php',{id:id},function(data){
        $('#retornoCategoria').show().html(data);
      });
      return false;
    }
    $('#formAltCategoria').submit(function(){
      $('#alterarCategoria').modal('hide');
      $('#Categorias').modal('hide');
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'alterar-categoria-item-estoque.php',
        data:$('#formAltCategoria').serialize(),
        success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(2500).html(data);
          window.setTimeout(function() { history.go(); }, 2500);
        }
      });
      return false;
    });
     //alterar fornecedor
     function alterarFornecedor(id){
      $('#alterarFornecedor').modal('show');
      $.get('retorno-fornecedor.php',{id:id},function(data){
        $('#retornoFornecedor').show().html(data);
      });
      return false;
    }
    $('#formAltFornecedor').submit(function(){
      $('#alterarFornecedor').modal('hide');
      $('#Fornecedores').modal('hide');
      $('#processando').modal('show');
      $.ajax({
        type:'post',
        url:'alterar-fornecedor-item-estoque.php',
        data:$('#formAltFornecedor').serialize(),
        success:function(data){
          $('#processando').modal('hide');
          $('#retorno').show().fadeOut(2500).html(data);
          window.setTimeout(function() { history.go(); }, 2500);
        }
      });
      return false;
    });
</script>