<?php
$idempresa = $_SESSION['idempresa'];
$tipouser = $_SESSION['tipouser'];
$iduser = $_SESSION['iduser'];
echo '
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">';
if (!empty($_SESSION['logomarcauser'])) {
  $filename = 'logocli/' . $_SESSION['logomarcauser'];
  if (file_exists($filename)) {
    echo '<img src="logocli/' . $_SESSION['logomarcauser'] . '" class="img-circle" alt="User Image">';
  } else {
    echo '<img src="dist/img/user.png" class="img-circle" alt="User Image">';
  }
} else {
  echo '<img src="dist/img/user.png" class="img-circle" alt="User Image">';
}
echo '
        </div>
        <div class="pull-left info">
          <p>' . primeiroNome(@$_SESSION['usuario']) . '</p>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->

      <ul class="sidebar-menu" data-widget="tree">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
       
        <li class="treeview clientes">
          <a href="#">
            <i class="fa fa-user"></i> <span>Cliente</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          
          <li id="clientes-busca"><a href="clientes.php"><i class="fa fa-circle-o text-primary"></i>Listar</a></li>
            <li id="clientes-pre"><a href="clientes-pre.php"><i class="fa fa-circle-o text-primary"></i>Pré-Cadastro</a></li>
          </ul>
        </li>

        <li class="treeview cto">
        <a href="#">
          <i class="fa fa-sitemap"></i> <span>Controle CTO</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li id="controle-cto"><a href="controle-cto.php"><i class="fa fa-circle-o  text-primary"></i> CTO</a></li>
        </ul>
      </li>

      <li id="chamados"><a href="chamados.php"><i class="fa fa-headphones"></i> <span>Chamados</span></a></li>
              
        <li class="treeview financeiro">
          <a href="#">
            <i class="fa fa-line-chart"></i> <span>Financeiro</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
  <li id="cobrancas"><a href="cobrancas.php"><i class="fa fa-circle-o text-primary"></i>Cobranças</a></li>
 <li id="controle-caixa"><a href="controle-de-caixa.php"><i class="fa fa-circle-o text-primary"></i>Controle de caixa</a></li>
<li id="gastos-mensais"><a href="gastos-mensais.php"><i class="fa fa-circle-o text-primary"></i>Gastos mensais</a></li>
  
          </ul>
        </li>       
        
        <li class="treeview mapa">
        <a href="#">
          <i class="fa fa-street-view"></i> <span>Mapa</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li id="mapa"><a href="mapa.php"><i class="fa fa-circle-o  text-primary"></i> Mapa</a></li>
        </ul>
      </li>

      <li class="treeview notificacoes">
      <a href="#">
        <i class="fa fa-commenting-o"></i> <span>Notificações</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li id="sms"><a href="sms.php"><i class="fa fa-circle-o text-primary"></i>SMS</a></li>
        <li id="notificacao-envia-manual"><a href="notificacao-envia-manual.php"><i class="fa fa-circle-o text-primary"></i>Notificação</a></li>
        <li id="notificacao-agendada-relatorio"><a href="notificacao-agendada-relatorio.php"><i class="fa fa-circle-o text-primary"></i>Relatório</a></li>
      </ul>
    </li>
        
        <li class="treeview parametros">
          <a href="#">
            <i class="fa fa-cogs"></i> <span>Parâmetros</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu"><li id="controle-estoque"><a href="controle-estoque.php"><i class="fa fa-circle-o text-primary"></i>Estoque</a></li>
              <li id="configuracoes"><a href="configuracoes-cobranca.php"><i class="fa fa-circle-o text-primary"></i>Parâmetros</a></li>
              <li id="bancos"><a href="bancos.php"><i class="fa fa-circle-o text-primary"></i>Bancos</a></li>
              <li id="usuarios"><a href="usuarios.php"><i class="fa fa-circle-o text-primary"></i>Usuários</a></li>
              <li id="servidor"><a href="servidor.php"><i class="fa fa-circle-o text-primary"></i>Servidor</a></li>
              <li id="importar-clientes"><a href="importar-clientes.php"><i class="fa fa-circle-o text-primary"></i>Importar clientes</a></li>
          </ul>
        </li>

        <li class="treeview relatorios">
        <a href="#">
          <i class="fa fa-file-text-o"></i> <span>Relatórios</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li id="relatorios-relatorio"><a href="relatorio-cliente.php"><i class="fa fa-circle-o"></i>Data de vencimento</a></li>
          <li id="relatorios-por-vencimento"><a href="clientes-por-vencimento.php"><i class="fa fa-circle-o text-primary"></i>Dia vencimento</a></li>
          <li id="relatorios-sem-cobranca"><a href="cobranca-relatorio-clientes-sem.php"><i class="fa fa-circle-o text-primary"></i>Sem cobrança</a></li>
          <li id="relatorios-relatorio-ativacao"><a href="relatorio-cliente-ativacao.php"><i class="fa fa-circle-o text-primary"></i>Data de ativação</a></li>
        </ul>
      </li>';
if ($_SESSION['sistema'] == 'master') {
  echo '
              <li class="treeview gestao">
                <a href="#">
                  <i class="fa fa-gear"></i> <span>Gestão</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li id="clientes2"><a href="clientes2.php"><i class="fa fa-circle-o  text-primary"></i>Usuários ISP</a></li>
                  <li id="vpn"><a href="vpn.php"><i class="fa fa-circle-o  text-primary"></i>Usuários VPN</a></li>
                  <li id="log-cobranca"><a href="log-cobrancas.php"><i class="fa fa-circle-o  text-primary"></i>Log cobranças</a></li>
                </ul>
              </li>';
}



echo '

        <li class="hidden"><a href="verificar-pendente.php" target="_blank"><i class="fa fa-circle-o  text-primary"></i>Verificar pendentes</a></li>
        <li><a href="verificar-vencidos-bloqueio.php?idempresa=' . $_SESSION['idempresa'] . '" target="_blank"><i class="fa fa-circle-o  text-primary"></i>Verificar e bloquear</a></li> 
        <li class="hidden"><a href="desbloqueio-geral.php?idempresa=' . $_SESSION['idempresa'] . '" target="_blank"><i class="fa fa-circle-o  text-primary"></i>Desbloquear todos</a></li>
        <li id="ajuda"><a href="ajuda.php"><i class="fa fa-circle-o  text-primary"></i><span>Ajuda/Configurações</span></a></li>
      </ul>
    </section>
  </aside>';
