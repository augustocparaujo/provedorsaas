<?php
function statusServer($id){
    include('conexao.php'); 
    require_once('routeros_api.class.php');
    $mk = new RouterosAPI();
    
    $id = $id;
    $query = mysqli_query($conexao,"SELECT * FROM servidor WHERE id='$id'") or die (mysqli_error($conexao));
    $dd = mysqli_fetch_array($query);

    if($mk->connect($dd['ip'], decrypt($dd['user']), decrypt($dd['password']))) {
       echo '<button class="btn btn-success btn-lrg ajax"><i class="fa fa-spin fa-refresh"></i> Conectado com sucesso</button>';
    }else{
        echo '<button class="btn btn-danger btn-lrg ajax"><i class="fa fa-spin fa-refresh"></i> Não consegui conectar</button>';
    }
}

?>