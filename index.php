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
  </head>
  <body>
    <?php
    hotn::get_overview();

    ?>

  </body>
</html>
