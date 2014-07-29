<?php
/**
 * Main class file for Hope of the Nations.
 */
include_once __dir__ . '/hotnConnector.php';
include_once __dir__ . '/hotnForm.php';
include_once __dir__ . '/hotnSponsorChildInterface.php';
include_once __dir__ . '/hotnSponsorChild.php';

class hotn {

  public static function get_overview() {
    hotnConnector::get_feed();
  }
}
