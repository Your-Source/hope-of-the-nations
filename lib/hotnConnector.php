<?php
/**
 * Connector class file for Hope of the Nations.
 */

class hotnConnector {

  /**
   * Get the feed width the data.
   * @param  string $type type of url
   * @param  array  $data extra query data
   * @return array        array width the data of the request.
   */
  public static function get_feed($type = 'child', $data = array()) {
    $sessionkey = 'hotn_' . $type;

    // If debug is true or session key is empty.
    if (hotnConfig::$debug || empty($_SESSION[$sessionkey])) {
      // Get the data from the request.
      $data = self::request($type, $data);

      // Set the data to the session.
      $_SESSION[$sessionkey] = $data;

      // Return array with the JSON data from url request..
      return json_decode($data);
    }

    // Return array with the JSON data from session.
    return json_decode($_SESSION[$sessionkey]);
  }

  /**
   * Request function for build the url en send request.
   * @param  string $type type of url
   * @param  array  $data extra query data
   * @return string       string with data of page.
   */
  private static function request($type = 'child', $data = array()) {
    // Build url with type.
    $url = hotnConfig::$url . '/' . hotnConfig::${$type . 'Url'};

    // Set api key to query and build query.
    $data['apikey'] = hotnConfig::$apikey;
    $query = http_build_query($data);

    // Create url with query.
    $url = $url . '?' . $query;

    // Get method by type.
    $method = hotnConfig::${$type . 'UrlMethod'};

    // Set the http options.
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => $method,
      ),
    );
    $context  = stream_context_create($options);
    // Get result with file get contents.
    $result = file_get_contents($url, FALSE, $context);

    return $result;
  }

}
