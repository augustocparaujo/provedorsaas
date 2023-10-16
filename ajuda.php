<?php
include('topo.php');
echo '
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">   
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-header with-border">
            <div class="col-lg-4">
              <h3 class="box-title">Ajuda e Configurações</h3>
              </div>
              <div class="col-lg-8">
              	<button class="btn btn-primary hidden" data-toggle="modal" data-target="#CadastrarChamado"><i class="fa fa-plus"></i> Cadastrar chamado</button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <code>
                           <center>   --> PAGINA DE BLOQUEIO  <-- </center> 
<br>
            <center>   Copie esses script e jogue no terminal do seu mikrotik </center>  
<br>
              
/ip firewall filter
add action=drop chain=forward comment=CORTE dst-port=!53 protocol=udp src-address-list=Bloqueados
add action=drop chain=forward comment=CORTE dst-port=!80,85,443,445 protocol=tcp src-address-list=Bloqueados
<br>
<br>

/ip firewall nat
add action=dst-nat chain=dstnat comment=CORTE_HTTPS dst-address=!89.163.224.149 dst-port=443 protocol=tcp src-address-list=Bloqueados to-addresses=89.163.224.149 to-ports=445
add action=dst-nat chain=dstnat comment=CORTE_HTTP dst-address=!89.163.224.149 dst-port=80 protocol=tcp src-address-list=Bloqueados to-addresses=89.163.224.149 to-ports=85
<br>
              </code>
              
                          </div>
            <!-- /.box-header -->
            <div class="box-body">
              <code>
              <center> --> CRIAR POOL <-- </center> <br>

              /ip pool
add name=Bloqueados ranges=172.100.0.0/21 <br>
              
              
              </code>

            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!--./col-xs-12-->      
      </div>
      <!--/.row-->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->';

include('rodape.php');
?>
<script>
  $('#ajuda').addClass('active');
</script>