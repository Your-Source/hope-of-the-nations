<?php
/**
 * Config file for Hope of the Nations.
 */

/**
 * Class hotnConfig.
 *
 * @param (bool) $debug
 *  TRUE for debugging, otherwise FALSE.
 * @param (string) $url
 *  The base URL of the REST interface.
 * @param (string) $apikey
 *  Identifier for the API.
 * @param (array) $childUrlData
 *  Array containing the URI and the http request method to get the sponsered
 *  children from the API.
 * @param (array) $sponsorUrlData
 *  Array containing the URI and the http request method to get
 *  the sponsors from the API.
 * @param (int) $session_ttl:
 *  Number of seconds (time to live) before the API response is refreshed
 *  and stored in the $_SESSION variable again.
 * @param (string) $translator_func
 *  Name of the function to translate content.
 *  This function can be user defined.
 * @param (string) $custom_translate_lang
 *  Language if not translate func is filled in.
 */
class hotnConfig {
  public static $url = 'http://hotn.administratiekoppeling.nl';
  public static $apikey = 'b9a695eee0986c7774f80c885649c278';
  public static $childUrlData = array(
    'uri' => 'api/children',
    'method' => 'GET',
    'content' => FALSE,
  );
  public static $sponsorUrlData = array(
    'uri' => 'api/sponsors',
    'method' => 'POST',
    'content' => TRUE,
  );
  public static $session_ttl = 1800;
  public static $translator_func = NULL;
  public static $custom_translate_lang = 'nl';
}
