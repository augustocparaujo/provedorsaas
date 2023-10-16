<?php 
include('topo.php');

echo'
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">

    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Log do servidor ativo no momento</h3>

              <div class="hidden box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>#</th>
                  <th>Data</th>
                  <th>Função</th>
                  <th>Mensagem</th>
                </tr>';                
                //se houver session mikrotik ativa
                  if(!empty($_SESSION['servidor'])){
                    $mk = new RouterosAPI();
                      if($mk->connect($_SESSION['ip'], decrypt($_SESSION['user']), decrypt($_SESSION['password']))) {
                      $n = 1;
                      $find = $mk->comm("/log/print");
                        if (count($find) >= 1) {
                          foreach ($find as $key => $value) {
                            echo '
                              <tr>
                                <td>'.$n.'</td>
                                <td>'.$find[$key]['time'].'</td>
                                <td>'.$find[$key]['topics'].'</td>
                                <td>'.$find[$key]['message'].'</td>
                            </tr>';
                            $n++;
                          }   
                        } else {
                            echo'não existe';
                        }
                  } else {
                    echo persona("Falha na conexão com: (".$_SESSION['ip'].")");
                  } }                            
                echo'
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';

include('rodape.php'); ?>
<script>
    $('#log').addClass('active');
</script>