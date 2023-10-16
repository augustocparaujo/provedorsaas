<?php
session_start();
include('conexao.php'); 
include('funcoes.php');
echo '
<div class="box-body table-responsive no-padding">
<table class="table table-hover">
    <tbody>
        <tr>
            <th>#</th>
            <th>Tipo</th>
            <th>Número</th>
            <th>Resumo</th>
            <th>Aberto</th>
            <th>Situação</th>
            <th>#</th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <a href="" class="fa fa-eye" aria-hidden="true" title=""></a>&ensp; 
                <a href="#" class="fa fa-trash text-red" aria-hidden="true" title=""></a>
            </td>
        </tr>
    </tbody>
</table>
</div>';
?>