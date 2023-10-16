<?php 
include('topo.php');
$id = $_GET['id'];
$query = mysqli_query($conexao,"SELECT j_estoque.*, j_categoria_estoque.nome_cat AS nomecategoria, j_fornecedor_equip.descricao AS nomefornecedor FROM j_estoque
LEFT JOIN j_categoria_estoque ON j_estoque.categoria = j_categoria_estoque.id
LEFT JOIN j_fornecedor_equip ON j_estoque.fornecedor = j_fornecedor_equip.id
WHERE j_estoque.id='$id'") or die (mysqli_error($conexao));
$ret = mysqli_fetch_array($query);
echo'
<input type="text" style="display:none" id="id" value="'.$id.'"/>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">  
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Exibindo Item</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                <div class="col-lg-12">
                    <label class="col-xs-12 col-lg-2 col-md-6 col-sm-12">Categoria
                        <input type="text" class="form-control" name="categoria" value="'.$ret['nomecategoria'].'" disabled/>
                    </label>
                    <label class="col-xs-12 col-lg-4 col-md-6 col-sm-12">Fornecedor
                        <input type="text" class="form-control" name="fornecedor" value="'.$ret['nomefornecedor'].'" disabled/>
                    </label>
                    <label class="col-xs-12 col-lg-4 col-md-12 col-sm-12">Descrição
                        <input type="text" class="form-control" placeholder="Descrição" name="descricao" value="'.AspasForm($ret['descricao']).'" disabled/>
                    </label>

                    <label class="col-xs-12 col-lg-2 col-md-12 col-sm-12">Quantidade
                        <input type="number" class="form-control" placeholder="Quantidade" name="quantidade" value="'.$ret['quantidade'].'" disabled/>
                    </label>
                </div>
                </div>
                <hr>
              <div class="box-body table-responsive no-padding">
              <table class="table table-hover">                
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>QUANT</th>
                        <th>Usuário cadastrou</th>
                        <th>Data cadastro</th>
                        <th>Usuário recebeu</th>
                        <th>Data saída</th>
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
<!-- /.content-wrapper -->';

include('rodape.php');
?>
<script>
    $('#controle-estoque').addClass('active');
    //tabela
    $(document).ready(function() { tabela(); });
    function tabela(){
        let id = $('#id').val();
      $.get('tab-saida-item-estoque.php',{id:id},function(data){
        $('#tabela').show().html(data);
      });
      return false;
    }
</script>