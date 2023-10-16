<?php
include('conexao.php'); 
$query = mysqli_query($conexao,"SELECT user.logomarca FROM user WHERE idempresa='$_GET[id]'");
$dd = mysqli_fetch_array($query);
echo'
<div class="col-12"><center>';
                if(!empty($dd['logomarca'])){ echo '<img src="logocli/'.@$dd['logomarca'].'"/>';} else { echo '<i class="text-red">sem logomarca</i>'; } 
              echo'
              
    <h1>
        Obrigado pelo seu cadastro<br>
        logo a equipe de atendimento<br>
        entrar em contato, aguarde.
    </h1></center>
</div>';
?>