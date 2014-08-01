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

    // If in the future get more children filter by request.
    // // Set API key to query and build query.
    // foreach ($data as $key => $value) {
    //   // Unset the key if the value is empty of not contains hotn-.
    //   if (empty($value) || strpos($key, 'hotn-')) {
    //     unset($data[$key]);
    //   }
    //   else {
    //     // Replace hotn- to nothing, set new parameter to array and delete old key.
    //     $new_key = str_replace('hotn-', '', $key);
    //     $data[$new_key] = $data[$key];
    //     unset($data[$key]);
    //   }
    // }

    // If session width data is empty or time to live of the session is expired.
    if (empty($_SESSION[$hotnsessionkey])
      || (time() - $_SESSION[$hotnsessionkey . '_created'] > hotnConfig::$session_ttl)) {

      // Get the data from the request.
      try {
        $data = self::type_data_request($type, $data);
      }
      catch (Exception $e) {};

      // Set the data to the session and set also a created time to session.
      try {
        $_SESSION[$hotnsessionkey] = $data;
        $_SESSION[$hotnsessionkey . '_created'] = time();
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
    $context = stream_context_create($options);
    // Get result with file get contents.
    try {
      $result = file_get_contents($url, FALSE, $context);
    }
    catch (Exception $e) {};

    return $result;
  }

}
