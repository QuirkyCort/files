<?php
  include 'default.php';
?>

<!DOCTYPE html>
<html>

<head>
  <title><?= $PAGE_TITLE ?></title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1">
  <meta name="google" value="notranslate">

  <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="font-awesome/all.min.css">
  <link rel="stylesheet" href="css/main.css?v=20220708">
  <link rel="stylesheet" href="css/widgets.css?v=20220708">
  <?= @$ADDITIONAL_STYLESHEETS ?>

  <script src="jquery/jquery-3.4.1.min.js"></script>
  <script src="popper/popper.min.js"></script>
  <script src="bootstrap/bootstrap.min.js"></script>
  <?= @$ADDITIONAL_SCRIPTS ?>

  <script src="js/common/defines.js?v=20220708"></script>
  <script src="js/common/util.js?v=20220708"></script>
  <script src="js/common/widgets.js?v=20220708"></script>
  <script src="js/common/ajaxRequest2.js?v=20220708"></script>
</head>
