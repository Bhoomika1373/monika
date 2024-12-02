<?php include "../includes/db.php"; ?>
<?php include "functions.php"; ?>
<?php ob_start(); ?>
<?php session_start(); ?>
<?php
    if(!isset($_SESSION['user_role'])){
        header("Location: ../home");
    }else if($_SESSION['user_role'] !== 'admin') {
        header("Location: ../home");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Goldenarm - Admin Panel</title>

      <link href="./../assets/img/favicon/favicon.ico" rel="icon">
      <link rel="apple-touch-icon" sizes="180x180" href="./../assets/img/favicon/apple-touch-icon.png">
      <link rel="icon" type="image/png" sizes="32x32" href="./../assets/img/favicon/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="16x16" href="./../assets/img/favicon/favicon-16x16.png">
      <link rel="manifest" href="./../assets/img/favicon/site.webmanifest">
      <link rel="mask-icon" href="./../assets/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
      <meta name="msapplication-TileColor" content="#da532c">
      <meta name="theme-color" content="#ffffff">

      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/sb-admin.css" rel="stylesheet">
      <link href="css/plugins/morris.css" rel="stylesheet">
      <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
      <script src="js/jquery.js"></script>
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
    </head>
    <body>
