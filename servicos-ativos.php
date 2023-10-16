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
              <h3 class="box-title">Serviços e portas ativos</h3>

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
                  <th>Serviço</th>
                  <th>Porta</th>
                </tr>';                
                //se houver session mikrotik ativa
                if(!empty($_SESSION['servidor'])){
                  $mk = new RouterosAPI();
                    if($mk->connect($_SESSION['ip'], decrypt($_SESSION['user']), decrypt($_SESSION['password']))) {
                      $n = 1;
                      $find = $mk->comm("/ip/service/print");
                      if (count($find) >= 1) {
                        foreach ($find as $key => $value) {
                          echo '
                            <tr>
                              <td>'.$n.'</td>
                              <td><i class="fa fa-circle text-green-vivo"></i>&ensp;'.$find[$key]['name'].'</td>
                              <td>'.$find[$key]['port'].'</td>
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
    $('#servicosativos').addClass('active');
</script>