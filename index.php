<?php
/**
 * @file index file for Hope of the Nations
 */
session_start();
include_once __dir__ . '/hotnConfig.php';
include_once __dir__ . '/lib/hotn.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Hope of the Nations</title>

    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/hotn.js"></script>

    <link rel="stylesheet" type="text/css" href="css/hotn-style.css">
  </head>
  <body>
    <?php
      echo hotn::get_overview();

    ?>

  </body>
</html>
