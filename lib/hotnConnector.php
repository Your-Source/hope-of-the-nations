<?php
/**
 * Connector class file for Hope of the Nations.
 */

class hotnConnector {

  /**
   * Get the feed width the data.
   * @param  string $type type of url
   * @param  array  $data extra query data
   * @return array array width the data of the request.
   */
  public static function get_feed($type = 'child', $data = array()) {
    $hotnsessionkey = 'hotn_' . $type;

    // If debug is true or session key is empty.
    if (hotnConfig::$debug || empty($_SESSION[$hotnsessionkey])) {
      // Get the data from the request.
      try {
        $data = self::type_data_request($type, $data);
      }
      catch (Exception $e) {};

      // Set the data to the session.
      try {
        $_SESSION[$hotnsessionkey] = $data;
      }
      catch (Exception $e) {};

      // Return array with the JSON data from url request..
      return json_decode($data, TRUE);
    }

    // Return array with the JSON data from session.
    return json_decode($_SESSION[$hotnsessionkey], TRUE);
  }

  /**
   * Request function for building the URL and sending requests.
   * @param  string $type type of URL.
   * @param  array  $data extra query data.
   * @return string string with data of page.
   */
  private static function type_data_request($type = 'child', $data = array()) {
    $url_data = hotnConfig::${$type . 'UrlData'};

    $url = hotnConfig::$url . '/' . $url_data['uri'];

    // Set API key to query and build query.
    foreach ($data as $key => $value) {
      // Unset the key if the value is empty.
      if (empty($value)) {
        unset($data[$key]);
      }
    }
    $data['apikey'] = hotnConfig::$apikey;
    $query = http_build_query($data);

    // Create url with query.
    $url = $url . '?' . $query;

    // Set the http options.
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => $url_data['method'],
      ),
    );
    $context  = stream_context_create($options);
    // Get result with file get contents.
    try {
      $result = file_get_contents($url, FALSE, $context);
    }
    catch (Exception $e) {};

    return $result;
  }

}
