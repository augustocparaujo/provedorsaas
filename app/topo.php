<?php

ob_start();
session_start();
include('../conexao.php');
include('../funcoes.php');
@$idempresa = $_SESSION['idempresa'];
@$nomeuser = $_SESSION['usuario'];
@$iduser = $_SESSION['iduser'];
if (isset($_SESSION['iduser']) != true) {
  echo '<script>location.href="sair.php";</script>';
}
?>

<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>MK-Gestor v1.0.0</title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport" />

  <meta name="apple-mobile-web-app-capable" content="yes" />

  <link rel="shortcut icon" href="../ico.png">

  <!-- Bootstrap 3.3.7 -->

  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->

  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Ionicons -->

  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">

  <!--funcybox-->

  <link rel="stylesheet" href="../plugins/fancybox/dist/jquery.fancybox.css" />

  <!-- Theme style -->

  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">

  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

  <style>
    .hidden {

      display: none;

    }



    .label-dark {

      background-color: #000
    }





    a {

      text-decoration: none !important;

    }



    .divRetorno {


      position: fixed;

      z-index: 1 !important;

      top: 10% !important;

      right: 0.5%;

      display: block;

      /*flutuando*/

      margin: 3px !important;

    }



    .button-canto-inferior {

      position: fixed;

      width: 60px;

      height: 60px;

      bottom: 40px;

      right: 40px;

      background-color: blue;

      color: #FFF;

      border-radius: 50px;

      text-align: center;

      font-size: 30px;

      box-shadow: 1px 1px 2px #888;

      z-index: 1000;

      text-decoration: none;

    }



    #map {

      width: 100%;

      height: 800px;

    }
  </style>

</head>

<body class="hold-transition skin-purple-light sidebar-mini">

  <div class="wrapper">

    <header class="main-header">

      <!-- Logo -->

      <a href="index.php" class="logo">

        <!-- mini logo for sidebar mini 50x50 pixels -->

        <span class="logo-mini"><b>MKG</b></span>

        <!-- logo for regular state and mobile devices -->

        <span class="logo-lg"><b>MK-Gestor</b></span>

      </a>



      <!-- Header Navbar: style can be found in header.less -->

      <nav class="navbar navbar-static-top">

        <!-- Sidebar toggle button-->

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">

          <span class="sr-only">Toggle navigation</span>

        </a>

        <!-- Navbar Right Menu -->

        <div class="navbar-custom-menu">

          <ul class="nav navbar-nav">

            <!-- Messages: style can be found in dropdown.less-->

            <li class="dropdown messages-menu hidden">

              <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                <i class="fa fa-clock-o"></i> <?php echo date('d-m-Y H:m'); ?></i>

              </a>

            </li>


          </ul>

        </div>

      </nav>

    </header>


    <?php include('menu.php'); ?>