<?php
/**
 * Config file for Hope of the Nations.
 */

class hotnConfig {
  public static $debug = FALSE;
  protected $url = 'http://hotn.administratiekoppeling.nl';
  public static $apikey = 'b9a695eee0986c7774f80c885649c278';
  public static $childUrlData = array(
    'uri' => 'api/children',
    'method' => 'GET',
  );
  public static $sponsorUrlData = array(
    'uri' => 'api/sponsors',
    'method' => 'POST',
  );
}
